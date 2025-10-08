const fs = require('fs');
const path = require('path');
const mime = require('mime-types');
const moment = require('moment');
const { spawn } = require('child_process');
const {
  S3,
  PutObjectCommand,
  CreateMultipartUploadCommand,
  UploadPartCommand,
  CompleteMultipartUploadCommand,
  AbortMultipartUploadCommand,
} = require('@aws-sdk/client-s3');
const { DynamoDBClient } = require('@aws-sdk/client-dynamodb');
const { DynamoDBDocumentClient, ScanCommand } = require('@aws-sdk/lib-dynamodb');

const TABLE_PREFIX = 'BPDiary';
const STAGE = process.env.ENVIRONMENT || 'local';

const TABLE = {
  diaries: `${TABLE_PREFIX}-diaries_${STAGE}`,
  memoOrders: `${TABLE_PREFIX}-memo_orders_${STAGE}`,
  settings: `${TABLE_PREFIX}-settings_${STAGE}`,
};

const TABLE_NAME_MAP = {
  [TABLE.diaries]: 'diaries',
  [TABLE.memoOrders]: 'memo_orders',
  [TABLE.settings]: 'settings',
};

// Processing Constants
const CSV_BATCH_SIZE = 10000;
const CSV_STREAM_BUFFER_SIZE = 32 * 1024 * 1024;
const CSV_FOLDER_NAME = 'diary_data';
const TOTAL_SEGMENTS = 16;
const MAX_RETRIES = 3;

// S3 Configuration
const S3_CONSTANTS = {
  MAX_RETRIES: 5,
  PART_SIZE: 5 * 1024 * 1024,
  STORAGE_CLASS: 'GLACIER_IR',
  DEFAULT_CONTENT_TYPE: 'application/octet-stream',
  ACL_PUBLIC_READ: 'public-read',
};

// Default Schema
const defaultSchemas = {
  diaries: {
    user_id: '',
    date: '',
    medicine_time1: null,
    medicine_time2: null,
    medicine_time3: null,
    memo_mask: null,
    memo_text: null,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  },
  memoOrders: {
    user_id: '',
    created_at: new Date().toISOString(),
    order: '',
    updated_at: new Date().toISOString(),
  },
  settings: {
    user_id: '',
    systolic_target: null,
    diastolic_target: null,
    medicine_per_day: null,
    medicine_alarm_time1: null,
    medicine_alarm_time2: null,
    medicine_alarm_time3: null,
    medicine_alarm_state: null,
    bp_morning_start: '04:00:00',
    bp_morning_end: '11:59:00',
    bp_evening_start: '19:00:00',
    bp_evening_end: '01:59:00',
    graph_type: 0,
    bp_scale_min_def: -1,
    bp_scale_max_def: -1,
    graph_mask: 1,
    initial_setting_done: null,
    tutorial_done_mask: null,
    memo_mask: null,
    medal_3_days: null,
    medal_5_days: null,
    medal_7_days: null,
    medal_14_days: null,
    medal_30_days: null,
    finish_initial: null,
    show_tutorial: null,
    on_boarding: null,
    finish_register: null,
    created_at: null,
    updated_at: null,
    email_popup_displayed: null,
    memo_icon_popup_displayed: 1,
    medicine_register: 1,
    logbook_separation_popup_displayed: 1,
    premium_popup_displayed: null,
    how_to_graph_popup_displayed: 1,
  },
};

// Utility Services
class LoggerService {
  static logInfo(message) {
    console.log(`${process.env.ENVIRONMENT}: ${message}\n`);
  }

  static logError(message) {
    console.error(`${process.env.ENVIRONMENT}: ${message}\n`);
  }

  static defaultWriteLog(message) {
    if (process.env.APP_REGION === 'EU') return;
    this.logInfo(message);
  }
}

class S3Service {
  constructor() {
    this.s3 = new S3({ maxAttempts: S3_CONSTANTS.MAX_RETRIES });
    this.MAX_RETRIES = S3_CONSTANTS.MAX_RETRIES;
    this.PART_SIZE = S3_CONSTANTS.PART_SIZE;
  }

  async uploadToS3(filePath, folderName) {
    const bucketName = process.env.AWS_BUCKET_S3_CSV;
    if (!bucketName) throw new Error('Missing S3 bucket name');
    if (!fs.existsSync(filePath)) throw new Error(`File not found: ${filePath}`);

    const fileSize = fs.statSync(filePath).size;
    const key = `${folderName}/${path.basename(filePath)}`;
    const contentType = mime.lookup(filePath) || S3_CONSTANTS.DEFAULT_CONTENT_TYPE;

    LoggerService.logInfo(`Uploading ${path.basename(filePath)} to bucket ${bucketName} at key ${key}`);
    await this.retryOperation(async () => {
      if (fileSize >= this.PART_SIZE) {
        await this.multipartUpload({ bucket: bucketName, key, filePath, contentType });
      } else {
        await this.simpleUpload({ bucket: bucketName, key, filePath, contentType });
      }
    });
    LoggerService.logInfo(`Upload completed for ${path.basename(filePath)}`);
  }

  async simpleUpload({ bucket, key, filePath, contentType }) {
    LoggerService.logInfo(`Starting simple upload for ${path.basename(filePath)}`);
    const fileContent = fs.readFileSync(filePath);
    await this.sendWithRetry(
      new PutObjectCommand({
        Bucket: bucket,
        Key: key,
        Body: fileContent,
        ContentType: contentType,
        ACL: S3_CONSTANTS.ACL_PUBLIC_READ,
        StorageClass: S3_CONSTANTS.STORAGE_CLASS,
      }),
    );
    LoggerService.logInfo(`Simple upload finished for ${path.basename(filePath)}`);
  }

  async multipartUpload({ bucket, key, filePath, contentType }) {
    LoggerService.logInfo(`Initiating multipart upload for ${path.basename(filePath)}`);
    let uploadId;
    const fileSize = fs.statSync(filePath).size;
    const parts = [];
    try {
      const createResponse = await this.sendWithRetry(
        new CreateMultipartUploadCommand({
          Bucket: bucket,
          Key: key,
          ContentType: contentType,
          ACL: S3_CONSTANTS.ACL_PUBLIC_READ,
          StorageClass: S3_CONSTANTS.STORAGE_CLASS,
        }),
      );
      uploadId = createResponse.UploadId;
      if (!uploadId) throw new Error('Failed to obtain uploadId');
      LoggerService.logInfo(`Multipart upload started. UploadId: ${uploadId}`);

      let partNumber = 1;
      const fileStream = fs.createReadStream(filePath, { highWaterMark: this.PART_SIZE });
      let uploadedBytes = 0;
      for await (const chunk of fileStream) {
        LoggerService.logInfo(`Uploading part ${partNumber} for ${path.basename(filePath)}`);
        const { ETag } = await this.retryOperation(() =>
          this.sendWithRetry(
            new UploadPartCommand({
              Bucket: bucket,
              Key: key,
              UploadId: uploadId,
              PartNumber: partNumber,
              Body: chunk,
            }),
          ),
        );
        parts.push({ ETag, PartNumber: partNumber });
        uploadedBytes += chunk.length;
        LoggerService.logInfo(
          `Uploaded part ${partNumber} (${((uploadedBytes / fileSize) * 100).toFixed(2)}% complete)`,
        );
        partNumber++;
      }

      await this.s3.send(
        new CompleteMultipartUploadCommand({
          Bucket: bucket,
          Key: key,
          UploadId: uploadId,
          MultipartUpload: { Parts: parts },
        }),
      );
      LoggerService.logInfo(`Multipart upload completed for ${path.basename(filePath)}`);
    } catch (error) {
      LoggerService.logError(`Error: Multipart upload failed: ${error.message}`);
      if (uploadId) {
        LoggerService.logInfo(`Aborting multipart upload. UploadId: ${uploadId}`);
        await this.s3.send(
          new AbortMultipartUploadCommand({
            Bucket: bucket,
            Key: key,
            UploadId: uploadId,
          }),
        );
      }
      throw error;
    }
  }

  async sendWithRetry(command, retries = 5, baseDelay = 500) {
    for (let attempt = 0; attempt <= retries; attempt++) {
      try {
        const result = await this.s3.send(command);
        return result;
      } catch (error) {
        if ((error.message.includes('socket hang up') || error.code === 'ECONNRESET') && attempt < retries) {
          const delay = baseDelay * Math.pow(2, attempt);
          LoggerService.logInfo(`Retrying in ${delay}ms...`);
          await new Promise((resolve) => setTimeout(resolve, delay));
          continue;
        }
        throw error;
      }
    }
  }

  async retryOperation(operation, retries = this.MAX_RETRIES) {
    try {
      return await operation();
    } catch (error) {
      if (
        retries > 0 &&
        (error.code === 'ThrottlingException' || error.name === 'ProvisionedThroughputExceededException')
      ) {
        const delay = Math.pow(2, this.MAX_RETRIES - retries) * 1000;
        LoggerService.logInfo(`Retryable error encountered. Retrying in ${delay}ms...`);
        await new Promise((resolve) => setTimeout(resolve, delay));
        return this.retryOperation(operation, retries - 1);
      }
      throw error;
    }
  }
}

class ExportCsvBatch {
  constructor() {
    this.s3Service = new S3Service();
    const ddbClient = new DynamoDBClient({ region: process.env.REGION });
    this.docClient = DynamoDBDocumentClient.from(ddbClient);
    this.BATCH_SIZE = CSV_BATCH_SIZE;
    this.WRITE_BUFFER_SIZE = CSV_STREAM_BUFFER_SIZE;
    this.tmpDir = '/tmp';
  }

  async execute() {
    LoggerService.logInfo('=== Starting CSV export batch process ===');
    const csvFiles = [];

    try {
      const tables = Object.values(TABLE);
      const tableResults = await this.processAllTables(tables);
      csvFiles.push(...tableResults);
      await this.uploadAllFiles(csvFiles);
      return { success: true };
    } catch (error) {
      LoggerService.logError(`Export process error: ${error.message}`);
      throw error;
    } finally {
      await this.cleanup(csvFiles);
    }
  }

  async processAllTables(tables) {
    LoggerService.logInfo(`Processing ${tables.length} tables: ${tables.join(', ')}`);
    return Promise.all(
      tables.map(async (table) => {
        LoggerService.logInfo(`Starting export for table: ${table}`);
        const csvFile = await this.getDataExport(table);
        LoggerService.logInfo(`Completed export for ${table}: ${csvFile}`);
        return csvFile;
      }),
    );
  }

  async uploadAllFiles(files) {
    LoggerService.logInfo('Starting S3 uploads...');
    await Promise.all(
      files.map((file) => {
        LoggerService.logInfo(`Initiating upload for: ${path.basename(file)}`);
        return this.s3Service.uploadToS3(file, CSV_FOLDER_NAME);
      }),
    );
    LoggerService.logInfo('All S3 uploads completed successfully');
  }

  // File Processing Methods
  async getDataExport(tableName) {
    const headerMap = {
      [TABLE.diaries]: Object.keys(defaultSchemas.diaries).filter((key) => key !== 'memo_text'),
      [TABLE.memoOrders]: Object.keys(defaultSchemas.memoOrders),
      [TABLE.settings]: Object.keys(defaultSchemas.settings).sort(),
    };

    const headers = headerMap[tableName];
    if (!headers) {
      throw new Error(`Unknown table: ${tableName}`);
    }

    return this.buildFileCSV(tableName, headers);
  }

  // File Building and Processing Methods
  async buildFileCSV(tableName, headers) {
    const fileName = `${TABLE_NAME_MAP[tableName]}_${moment().format('YYYYMMDD')}.csv`;
    const filePath = path.join(this.tmpDir, fileName);

    try {
      const UTF8_BOM = Buffer.from([0xEF, 0xBB, 0xBF]);
      fs.writeFileSync(filePath, UTF8_BOM);
      const headerLine = headers.map((h) => `"${h.replace(/"/g, '\\"')}"`).join(',') + '\n';
      fs.appendFileSync(filePath, headerLine);

      const segmentDir = path.join(this.tmpDir, `segments_${Date.now()}`);
      fs.mkdirSync(segmentDir, { recursive: true });

      const segmentFiles = await Promise.all(
        Array.from({ length: TOTAL_SEGMENTS }, (_, segment) =>
          this.processSegment(tableName, headers, segment, TOTAL_SEGMENTS, segmentDir),
        ),
      );

      // Merge all segment files
      await this.mergeSortedFiles(segmentFiles, filePath, tableName, headers);

      // Cleanup segment files and directory
      await this.cleanupFiles(segmentFiles);
      fs.rmdirSync(segmentDir);

      return filePath;
    } catch (error) {
      LoggerService.logError(`Failed to build CSV for ${tableName}: ${error.message}`);
      throw error;
    }
  }

  async processSegment(tableName, headers, segment, totalSegments, segmentDir) {
    LoggerService.logInfo(`Processing segment ${segment + 1}/${totalSegments} for ${tableName}`);
    const segmentFile = path.join(segmentDir, `segment_${segment}.csv`);
    const writeStream = fs.createWriteStream(segmentFile, {
      flags: 'a',
      highWaterMark: this.WRITE_BUFFER_SIZE,
    });

    let lastEvaluatedKey;
    let itemCount = 0;
    let batchCount = 0;

    try {
      while (true) {
        const { Items = [], LastEvaluatedKey } = await this.scanSegment(
          tableName,
          segment,
          totalSegments,
          lastEvaluatedKey,
        );

        if (Items.length > 0) {
          batchCount++;
          itemCount += Items.length;

          LoggerService.logInfo(
            `Segment ${segment + 1}: Processed batch ${batchCount} with ${Items.length} items (Total: ${itemCount}) for ${tableName}`,
          );

          // Write items directly without sorting
          for (const item of Items) {
            const values = headers.map((header) => item[header] ?? '');
            writeStream.write(this.formatCSVLine(values) + '\n');
          }
        }

        if (!LastEvaluatedKey) break;
        lastEvaluatedKey = LastEvaluatedKey;
      }

      await new Promise((resolve) => writeStream.end(resolve));
      // Sort the segment file after it's complete
      LoggerService.logInfo(`Sorting segment file ${segment + 1}`);
      const sortedFile = path.join(segmentDir, `sorted_segment_${segment}.csv`);
      await this.sortFile(segmentFile, sortedFile, tableName, headers);
      fs.unlinkSync(segmentFile);

      LoggerService.logInfo(`Completed segment ${segment + 1} for ${tableName}. Total items: ${itemCount}`);
      return sortedFile;
    } catch (error) {
      writeStream.end();
      throw error;
    }
  }

  async sortFile(inputFile, outputFile, tableName, headers) {
    return new Promise((resolve, reject) => {
      const sortKey = headers.indexOf('user_id') + 1;
      const sortArgs = ['-S', '6G', '-t', ',', '-k', `${sortKey},${sortKey}`];

      if (tableName === TABLE.diaries) {
        const dateKey = headers.indexOf('date') + 1;
        sortArgs.push('-k', `${dateKey},${dateKey}`);
      }

      sortArgs.push('-o', outputFile, inputFile);

      LoggerService.logInfo(`Sorting file: sort ${sortArgs.join(' ')}`);
      const sortProcess = spawn('sort', sortArgs, {
        env: { LC_ALL: 'C' },
      });

      sortProcess.stderr.on('data', (data) => {
        LoggerService.logError(`Sort process error: ${data}`);
      });

      sortProcess.on('close', (code) => {
        if (code !== 0) {
          reject(new Error(`Sort failed with code ${code}`));
          return;
        }
        resolve();
      });
    });
  }

  async mergeSortedFiles(inputFiles, outputFile, tableName, headers) {
    LoggerService.logInfo(`Merging ${inputFiles.length} sorted files for ${tableName}`);
    return new Promise((resolve, reject) => {
      const isFinalOutput = !outputFile.includes('intermediate_') && !outputFile.includes('segment_');
      let headerContent = '';
      if (isFinalOutput && fs.existsSync(outputFile)) {
        headerContent = fs.readFileSync(outputFile, 'utf8').split('\n')[0] + '\n';
      }

      const sortKey = headers.indexOf('user_id') + 1;
      const sortArgs = ['-m', '--parallel=4', '-S', '6G', '-t', ',', '-k', `${sortKey},${sortKey}`];

      if (tableName === TABLE.diaries) {
        const dateKey = headers.indexOf('date') + 1;
        sortArgs.push('-k', `${dateKey},${dateKey}`);
      }

      sortArgs.push('-o', outputFile, ...inputFiles);

      LoggerService.logInfo(`Merge command: sort ${sortArgs.join(' ')}`);
      const mergeProcess = spawn('sort', sortArgs, {
        env: { LC_ALL: 'C' },
      });

      mergeProcess.stderr.on('data', (data) => {
        LoggerService.logError(`Merge process error: ${data}`);
      });

      mergeProcess.on('close', async (code) => {
        if (code !== 0) {
          reject(new Error(`Merge failed with code ${code}`));
          return;
        }

        if (isFinalOutput && headerContent) {
          // Prepend header to the merged file
          const tempFile = outputFile + '.tmp';
          fs.renameSync(outputFile, tempFile);
          const writeStream = fs.createWriteStream(outputFile);
          writeStream.write(headerContent);
          fs.createReadStream(tempFile).pipe(writeStream);
          await new Promise((res) => writeStream.on('finish', res));
          fs.unlinkSync(tempFile);
        }

        resolve();
      });
    });
  }

  async scanSegment(tableName, segment, totalSegments, startKey, retries = MAX_RETRIES) {
    const params = {
      TableName: tableName,
      Segment: segment,
      TotalSegments: totalSegments,
      Limit: this.BATCH_SIZE,
      ExclusiveStartKey: startKey,
    };

    for (let attempt = 0; attempt <= retries; attempt++) {
      try {
        const command = new ScanCommand(params);
        const result = await this.docClient.send(command);
        return {
          Items: result.Items || [],
          LastEvaluatedKey: result.LastEvaluatedKey,
        };
      } catch (error) {
        if (attempt === retries || error.name !== 'ProvisionedThroughputExceededException') {
          throw error;
        }
        await new Promise((resolve) => setTimeout(resolve, Math.pow(2, attempt) * 1000));
      }
    }
    throw new Error('Max retries exceeded');
  }

  formatCSVLine(values) {
    return values
      .map((v) => {
      const escaped = (v ?? '').toString()
        .replace(/\\/g, '\\\\')
        .replace(/"/g, '\\"');
      return `"${escaped}"`;
    }).join(',');
  }

  async cleanup(files) {
    LoggerService.logInfo('Cleaning up temporary files...');
    await this.cleanupFiles(files);
    LoggerService.logInfo('Cleanup completed');
  }

  async cleanupFiles(files) {
    for (const file of files) {
      try {
        if (fs.existsSync(file)) {
          await fs.promises.unlink(file);
        }
      } catch (error) {
        LoggerService.logError(`Error cleaning up file ${file}: ${error.message}`);
      }
    }
  }
}

async function validateEnvironment() {
  const requiredEnv = ['AWS_BUCKET_S3_CSV', 'REGION', 'ENVIRONMENT'];
  const missingVars = requiredEnv.filter((varName) => !process.env[varName]);
  if (missingVars.length > 0) {
    throw new Error(`Missing required environment variables: ${missingVars.join(', ')}`);
  }
}

async function main() {
  try {
    await validateEnvironment();
    const batchJob = new ExportCsvBatch();
    const result = await batchJob.execute();
    if (!result.success) {
      throw new Error('Batch job failed to complete successfully');
    }
    console.log('CSV export batch job completed successfully');
    process.exit(0);
  } catch (error) {
    console.error('Error in batch job:', error);
    LoggerService.defaultWriteLog(`Batch job failed: ${error.message}`);
    process.exit(1);
  }
}

if (require.main === module) {
  main();
}

module.exports = { ExportCsvBatch };
