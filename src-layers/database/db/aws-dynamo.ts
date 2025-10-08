/**
* BP-api-serverless
* Created by LoiNV <loinv@vitalify.asia> on 2024/10/09
* Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import {
  CreateTableCommand,
  CreateTableCommandInput,
  CreateTableCommandOutput,
  DeleteRequest,
  DeleteTableCommand,
  DeleteTableCommandInput,
  DeleteTableCommandOutput,
  DynamoDBClient,
  DynamoDBClientConfig,
  KeysAndAttributes,
  PutRequest,
  WriteRequest,
  DescribeTableCommand,
  DescribeTableCommandInput,
} from '@aws-sdk/client-dynamodb';
import {
  DynamoDBDocumentClient,
  PutCommand,
  UpdateCommand,
  DeleteCommand,
  ScanCommand,
  QueryCommand,
  PutCommandInput,
  GetCommandInput,
  GetCommand,
  GetCommandOutput,
  PutCommandOutput,
  DeleteCommandInput,
  DeleteCommandOutput,
  UpdateCommandOutput,
  UpdateCommandInput,
  QueryCommandInput,
  QueryCommandOutput,
  ScanCommandInput,
  ScanCommandOutput,
  BatchGetCommand,
  BatchGetCommandInput,
  NativeAttributeValue,
  BatchWriteCommand,
  BatchWriteCommandInput,
  BatchWriteCommandOutput,
} from '@aws-sdk/lib-dynamodb';
import { HttpHandlerOptions } from '@smithy/types';
import { LOCAL_DB_ENDPOINT } from '/opt/constants';
import { LoggerService } from '/opt/services';

// Interface for a record
export interface DynamoDBItem {
  [key: string]: any;
}

// Input, output type for the put command
export type IPutCommandInput<T extends DynamoDBItem> = Omit<PutCommandInput, 'TableName' | 'Item'> & {
  Item: T,
};
export type IPutCommandOutput = PutCommandOutput;

// Input, output for the get command
export type IGetCommandInput = Omit<GetCommandInput, 'TableName'>;
export type IGetCommandOutput<T extends DynamoDBItem> = Omit<GetCommandOutput, 'Item'> & {
  Item?: T,
};

// Input, output for the update command
export type IUpdateCommandInput = Omit<UpdateCommandInput, 'TableName'>;
export type IUpdateCommandOutput = UpdateCommandOutput;

// Input, output for the delete command
export type IDeleteCommandInput = Omit<DeleteCommandInput, 'TableName'>;
export type IDeleteCommandOutput = DeleteCommandOutput;

// Input, output for the query command
export type IQueryCommandInput = Omit<QueryCommandInput, 'TableName'>;
export type IQueryCommandOutput<T extends DynamoDBItem> = Omit<QueryCommandOutput, 'Items'> & {
  Items?: T[],
};

// Input, output for the scan command
export type IScanCommandInput = Omit<ScanCommandInput, 'TableName'>;
export type IScanCommandOutput<T extends DynamoDBItem> = Omit<ScanCommandOutput, 'Items'> & {
  Items?: T[],
};

// Input, output for the batch get item command
export type BatchGetItemInput = Omit<KeysAndAttributes, "Keys"> & {
  Keys: Record<string, NativeAttributeValue>[] | undefined;
};
export type BatchGetItemOutput<T extends DynamoDBItem> = {
  $metadata: any,
  UnprocessedKeys: any
  Items?: T[],
}

// Input, output for the batch write item command
export type BatchWriteItemInput<T extends DynamoDBItem> = (Omit<WriteRequest, "PutRequest" | "DeleteRequest"> & {
  PutRequest?: Omit<PutRequest, "Item"> & {
      Item: T,
  },
  DeleteRequest?: Omit<DeleteRequest, "Key"> & {
      Key: Record<string, NativeAttributeValue> | undefined;
  },
})[];
export type BatchWriteItemOutput<T extends DynamoDBItem> = Omit<BatchWriteCommandOutput, "UnprocessedItems"> & {
  UnprocessedItems?: Record<string, (Omit<WriteRequest, "PutRequest" | "DeleteRequest"> & {
      PutRequest?: Omit<PutRequest, "Item"> & {
          Item: T;
      };
      DeleteRequest?: Omit<DeleteRequest, "Key"> & {
          Key: Record<string, NativeAttributeValue> | undefined;
      };
  })[]>;
};

// Input, output for the create table command
export type ICreateTableCommandInput = Omit<CreateTableCommandInput, 'TableName'>;
export type ICreateTableCommandOutput = CreateTableCommandOutput

// Input, output for the delete table command
export type IDeleteTableCommandOutput = DeleteTableCommandOutput

// For singleton pattern
let client: DynamoDBClient | null = null;
let docClient: DynamoDBDocumentClient | null = null;

// Abstract class to init a Model DynamoDB instance
export abstract class AWSDynamo<T extends DynamoDBItem> {
  public readonly tableName: string;
  private readonly client: DynamoDBClient;
  private readonly docClient: DynamoDBDocumentClient;

  protected constructor(tableName: string) {
    this.tableName = tableName;

    // Configuration for the DynamoDB connection
    const options: DynamoDBClientConfig = {
      maxAttempts: 10,
    };
    if (process.env.STAGE === 'local') {
      options.region = process.env.REGION;
      options.endpoint = LOCAL_DB_ENDPOINT;
    }

    // This is pattern to make sure client and docClient is just created one time when we connect with DynamoDB
    if (!client || !docClient) {
      client = new DynamoDBClient(options);
      docClient = DynamoDBDocumentClient.from(client);
    }

    this.client = client
    this.docClient = docClient;
  }

  /**
  * Add new item to database
  * @param {IPutCommandInput} input - Put command input
  * @return {IPutCommandOutput} - Output contains data and metadata
  */
  async putItem(input: IPutCommandInput<T>, options?: HttpHandlerOptions): Promise<IPutCommandOutput> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as PutCommandInput;

    // Execute and return
    const result = await this.docClient.send(new PutCommand(params), options) as IPutCommandOutput;
    return result;
  }

  /**
  * Get item from database
  * @param {IGetCommandInput} input - Get command input
  * @return {IGetCommandOutput} - Output contains data and metadata
  */
  async getItem(input: IGetCommandInput, options?: HttpHandlerOptions): Promise<IGetCommandOutput<T>> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as GetCommandInput;

    // Execute and return
    const result = await this.docClient.send(new GetCommand(params), options) as IGetCommandOutput<T>;
    return result;
  }

  /**
  * Update item from database
  * @param {IUpdateCommandInput} input - Update command input
  * @return {IUpdateCommandOutput} - Output contains data and metadata
  */
  async updateItem(input: IUpdateCommandInput, options?: HttpHandlerOptions): Promise<IUpdateCommandOutput> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as UpdateCommandInput;

    // Execute and return
    const result = await this.docClient.send(new UpdateCommand(params), options) as IUpdateCommandOutput;
    return result;
  }

  /**
  * Delete item from database
  * @param {IDeleteCommandInput} input - Delete command input
  * @return {IDeleteCommandOutput} - Output contains data and metadata
  */
  async deleteItem(input: IDeleteCommandInput, options?: HttpHandlerOptions): Promise<IDeleteCommandOutput> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as DeleteCommandInput;

    // Execute and return
    const result = await this.docClient.send(new DeleteCommand(params), options) as IDeleteCommandOutput;
    return result;
  }

  /**
  * Query items from database
  * @param {IQueryCommandInput} input - Query command input
  * @return {IQueryCommandOutput} - Output contains data and metadata
  */
  async query(input: IQueryCommandInput, options?: HttpHandlerOptions): Promise<IQueryCommandOutput<T>> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as QueryCommandInput;

    // Execute and return
    const result = await this.docClient.send(new QueryCommand(params), options) as IQueryCommandOutput<T>;
    return result;
  }

  /**
  * Scan items from database
  * @param {IScanCommandInput} input - Scan command input
  * @return {IScanCommandOutput} - Output contains data and metadata
  */
  async scan(input?: IScanCommandInput, options?: HttpHandlerOptions): Promise<IScanCommandOutput<T>> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as ScanCommandInput;

    // Execute and return
    const result = await this.docClient.send(new ScanCommand(params), options) as IScanCommandOutput<T>;
    return result;
  }

  /**
  * Batch get items from database
  * @param {BatchGetItemInput} input - Keys and Configs
  * @return {BatchGetItemOutput} - Output contains data and metadata
  */
  async batchGetItem(input: BatchGetItemInput, options?: HttpHandlerOptions): Promise<BatchGetItemOutput<T>> {
    // Create command input
    const params = {
      RequestItems: {
        [this.tableName]: input
      }
    } as BatchGetCommandInput;

    // Execute and return
    const result = await this.docClient.send(new BatchGetCommand(params), options);
    return {
      $metadata: result.$metadata,
      UnprocessedKeys: result.UnprocessedKeys,
      Items: result.Responses?.[this.tableName] || [],
    } as BatchGetItemOutput<T>;
  }

  /**
  * Batch write items to database
  * @param {BatchWriteItemInput} input - Data and configs
  * @return {BatchWriteItemOutput} - Output contains data and metadata
  */
  async batchWriteItem(input: BatchWriteItemInput<T>, options?: HttpHandlerOptions): Promise<BatchWriteItemOutput<T>> {
    // Create command input
    const params = {
      RequestItems: {
        [this.tableName]: input
      }
    } as BatchWriteCommandInput;

    // Execute and return
    const result = await this.docClient.send(new BatchWriteCommand(params), options) as BatchWriteItemOutput<T>;
    return result;
  }

  /**
  * Create a table
  * @param {ICreateTableCommandInput} input - Configs for table
  * @return {ICreateTableCommandOutput} - Table infomation
  */
  async createTable(input: ICreateTableCommandInput, options?: HttpHandlerOptions): Promise<ICreateTableCommandOutput> {
    // Create command input
    const params = {
      TableName: this.tableName,
      ...input,
    } as CreateTableCommandInput;

    // Execute and return
    const result = await this.client.send(new CreateTableCommand(params), options) as ICreateTableCommandOutput;
    return result;
  }

  /**
  * Delete a table
  * @return {IDeleteTableCommandOutput} - Deleting infomation
  */
  async deleteTable(options?: HttpHandlerOptions): Promise<IDeleteTableCommandOutput> {
    // Create command input
    const params = {
      TableName: this.tableName,
    } as DeleteTableCommandInput;

    // Execute and return
    const result = await this.client.send(new DeleteTableCommand(params), options) as IDeleteTableCommandOutput;
    return result;
  }

  /**
 * Batch write items to database
 * @param {BatchWriteCommandInput} input - Data and configs
 * @return {BatchWriteCommandOutput} - Output contains data and metadata
 */
  async batchWrite(
    input: Omit<BatchWriteCommandInput, 'RequestItems'> & {
      RequestItems: Record<string, WriteRequest[]>;
    },
    options?: HttpHandlerOptions
  ): Promise<BatchWriteCommandOutput> {
    // Create command input
    const params: BatchWriteCommandInput = {
      RequestItems: {
        [this.tableName]: input.RequestItems[this.tableName],
      },
    };

    // Execute and return
    const result = await this.docClient.send(new BatchWriteCommand(params), options);
    return result;
  }

  /**
   * Reset a table (delete and create)
   * @param {ICreateTableCommandInput} createInput - Configs for table creation
   * @return {void}
   */
  async resetTable(createInput: ICreateTableCommandInput, options?: HttpHandlerOptions): Promise<void> {
    // Check if the table exists
    const describeParams = {
      TableName: this.tableName,
    } as DescribeTableCommandInput;

    try {
      await this.client.send(new DescribeTableCommand(describeParams), options);
      // Table exists, delete it and wait for deletion to complete
      await this.deleteTable(options);
      // Wait for the table to be completely deleted
      await this.waitForTableDeletion(options);
    } catch (error) {
      if (error instanceof Error && error.name !== 'ResourceNotFoundException') {
        // If error is not "Table not found", rethrow it
        throw error;
      }
      // Table not found, continue to create
    }

    // Create the table
    await this.createTable(createInput, options);
    // Wait for the table to become ACTIVE
    await this.waitForTableActive();
  }

  /**
   * Wait for table deletion to complete
   * @param {HttpHandlerOptions} options - Handler options
   * @return {Promise<void>}
   */
  private async waitForTableDeletion(options?: HttpHandlerOptions): Promise<void> {
    const describeParams = {
      TableName: this.tableName,
    } as DescribeTableCommandInput;

    // eslint-disable-next-line no-constant-condition
    while (true) {
      try {
        await this.client.send(new DescribeTableCommand(describeParams), options);
        // If no error, table still exists, wait and retry
        await new Promise((resolve) => setTimeout(resolve, 5000));
      } catch (error) {
        if (error instanceof Error && error.name === 'ResourceNotFoundException') {
          // Table not found, deletion complete
          break;
        }
        // If other error, rethrow it
        throw error;
      }
    }
  }

  /**
   * Waits for the table to become ACTIVE
   * @param {HttpHandlerOptions} options - Additional options for the handler
   * @return {Promise<void>}
   */
  private async waitForTableActive(options?: HttpHandlerOptions): Promise<void> {
    const describeParams = {
      TableName: this.tableName,
    } as DescribeTableCommandInput;

    // eslint-disable-next-line no-constant-condition
    while (true) {
      // eslint-disable-next-line no-useless-catch
      try {
        const result = await this.client.send(new DescribeTableCommand(describeParams), options);
        if (result.Table && result.Table.TableStatus === 'ACTIVE') {
          // Table is active
          return;
        }
        // If table is not active, wait and retry
        await new Promise((resolve) => setTimeout(resolve, 5000));
      } catch (error) {
        LoggerService.logError(`Error table ${this.tableName} is not active: ${error}`);
        throw error;
      }
    }
  }

  /**
   * Check if the table exists
   * @return {Promise<boolean>} - True if table exists, false otherwise
   */
  async checkTableExists(options?: HttpHandlerOptions): Promise<boolean> {
    try {
      const describeParams = {
        TableName: this.tableName,
      } as DescribeTableCommandInput;
      
      await this.client.send(new DescribeTableCommand(describeParams), options);
      return true;
    } catch (error) {
      if (error instanceof Error && error.name === 'ResourceNotFoundException') {
        return false;
      }
      throw error; // Rethrow other errors
    }
  }
}
