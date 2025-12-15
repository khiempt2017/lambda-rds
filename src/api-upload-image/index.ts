/**
* BP-api-serverless
* Upload Image to S3 API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/12/15
*/

import { LoggerService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, loggingMiddleware } from '/opt/middlewares';
import { responseAPIGatewaySuccess, responseAPIGatewayError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';
import { S3Client, PutObjectCommand, HeadBucketCommand } from '@aws-sdk/client-s3';

const BUCKET_NAME = process.env.S3_BUCKET_NAME || '';
const BUCKET_REGION = 'ap-southeast-1';
const FILE_NAME = 'pic.png'; // Hard-coded filename

// Create S3 client with correct region
const s3Client = new S3Client({ 
  region: BUCKET_REGION,
  // For local testing with real S3, use default credentials
  // For LocalStack, uncomment below:
  // endpoint: 'http://localhost:4566',
  // forcePathStyle: true,
});

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Upload Image API ===');
    
    if (!BUCKET_NAME) {
      LoggerService.logError('S3_BUCKET_NAME environment variable is not set');
      return responseAPIGatewayError({
        message: 'Server configuration error: S3_BUCKET_NAME not set',
      }, 500);
    }

    LoggerService.logInfo(`Using S3 Bucket: ${BUCKET_NAME}`);
    LoggerService.logInfo(`Using S3 Region: ${BUCKET_REGION}`);

    // Get image data from request body
    const body = event.body;
    if (!body) {
      return responseAPIGatewayError({
        message: 'No image data provided',
      }, 400);
    }

    // Decode base64 image
    const imageBuffer = Buffer.from(body, 'base64');
    
    LoggerService.logInfo(`Uploading image to S3: ${BUCKET_NAME}/${FILE_NAME}`);
    LoggerService.logInfo(`Image size: ${imageBuffer.length} bytes`);

    // First, verify bucket exists and is accessible
    try {
      const headCommand = new HeadBucketCommand({ Bucket: BUCKET_NAME });
      await s3Client.send(headCommand);
      LoggerService.logInfo('Bucket is accessible');
    } catch (error: any) {
      LoggerService.logError(`Bucket not accessible: ${error.message}`);
      return responseAPIGatewayError({
        message: 'S3 Bucket not accessible',
        error: `Bucket: ${BUCKET_NAME}, Region: ${BUCKET_REGION}, Error: ${error.message}`,
      }, 500);
    }

    // Upload to S3
    const command = new PutObjectCommand({
      Bucket: BUCKET_NAME,
      Key: FILE_NAME,
      Body: imageBuffer,
      ContentType: 'image/png',
      CacheControl: 'no-cache', // Force browser to always get latest version
    });

    await s3Client.send(command);
    
    // Use region-specific URL format
    const imageUrl = `https://${BUCKET_NAME}.s3.${BUCKET_REGION}.amazonaws.com/${FILE_NAME}`;
    
    LoggerService.logInfo(`Image uploaded successfully: ${imageUrl}`);

    return responseAPIGatewaySuccess({
      message: 'Image uploaded successfully',
      data: {
        fileName: FILE_NAME,
        url: imageUrl,
        size: imageBuffer.length,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Upload Image API: ' + JSON.stringify(error));
    return responseAPIGatewayError({
      message: 'Image upload failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    }, 500);
  }
}

const handler = middy(lambdaHandler)
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
