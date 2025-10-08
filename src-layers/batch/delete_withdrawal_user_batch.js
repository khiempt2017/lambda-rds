const { DynamoDBClient } = require('@aws-sdk/client-dynamodb');
const { 
  DynamoDBDocumentClient, 
  QueryCommand, 
  BatchWriteCommand,
  ScanCommand,
} = require('@aws-sdk/lib-dynamodb');

const DYNAMODB_BATCH_WRITE_LIMIT = 25;
const STAGE = process.env.ENVIRONMENT;
class DeleteUserDataBatch {
  constructor() {
    this.dynamoClient = new DynamoDBClient({ region: process.env.REGION });
    this.docClient = DynamoDBDocumentClient.from(this.dynamoClient);
  }

  async execute(userIDList) {
    console.log(`Starting batch job to delete data`);
    console.log("REGION: " + process.env.REGION);
    
    // Define tables to process
    const tablesToProcess = [
      `BPDiary-banner_management_${STAGE}`,
      `BPDiary-diaries_${STAGE}`,
      `BPDiary-memo_orders_${STAGE}`,
      `BPDiary-settings_${STAGE}`
    ];

    // Extract user IDs from the array of objects
    const userIds = userIDList.map(item => item.user_id);
    console.log(`Processing for users: ${userIds}`);

    // Process each table
    for (const tableName of tablesToProcess) {
      console.log(`Processing table: ${tableName}`);
      for (const userId of userIds) {
        try {
          // Query items to delete for this user
          const itemsToDelete = await this.queryItemsByUserId(userId, tableName);
          console.log("itemsToDelete: " + JSON.stringify(itemsToDelete));
          console.log(`Found ${itemsToDelete.length} items for user ID ${userId}`);

          if (itemsToDelete.length === 0) {
            console.log(`No items to delete for user ID ${userId}`);
            continue;
          }

          // Process items in batches due to DynamoDB limits
          const batchSize = DYNAMODB_BATCH_WRITE_LIMIT;
          for (let i = 0; i < itemsToDelete.length; i += batchSize) {
            const batch = itemsToDelete.slice(i, i + batchSize);
            const deleteRequests = batch.map(item => {
              let key = { user_id: item.user_id };
              
              // Add additional key attributes based on table name
              if (tableName === `BPDiary-diaries_${STAGE}`) {
                key.date = item.date;
              } else if (tableName === `BPDiary-banner_management_${STAGE}`) {
                key.banner_category = item.banner_category;
              }
              
              return {
                DeleteRequest: { Key: key }
              };
            });

            // Delete items and handle unprocessed items
            let unprocessedItems = deleteRequests;
            do {
              console.log("unprocessedItems: " + JSON.stringify(unprocessedItems));
              const command = new BatchWriteCommand({
                RequestItems: {
                  [tableName]: unprocessedItems
                }
              });
              
              const response = await this.docClient.send(command);
              console.log("response: " + JSON.stringify(response));

              unprocessedItems = [];
              if (response.UnprocessedItems && response.UnprocessedItems[tableName]) {
                unprocessedItems = response.UnprocessedItems[tableName];
                console.log(`Retrying ${unprocessedItems.length} unprocessed items`);
                // Add small delay before retry
                await new Promise(resolve => setTimeout(resolve, 1000));
              }
            } while (unprocessedItems.length > 0);

            console.log(`Successfully deleted ${batch.length} items for user ID ${userId}`);
          }
        } catch (error) {
          console.error(`Error processing user ID ${userId} in table ${tableName}:`, error);
          throw error;
        }
      }
      console.log(`Completed deletion in table ${tableName}`);
    }
  }

  async queryItemsByUserId(userId, tableName) {
    let items = [];
    let lastEvaluatedKey;

    do {
      const params = {
        TableName: tableName,
        KeyConditionExpression: 'user_id = :user_id',
        ExpressionAttributeValues: {
          ':user_id': userId
        },
        ExclusiveStartKey: lastEvaluatedKey
      };

      const command = new QueryCommand(params);
      const result = await this.docClient.send(command);
      console.log("result: " + JSON.stringify(result));
      if (result.Items) {
        items = items.concat(result.Items);
      }
      lastEvaluatedKey = result.LastEvaluatedKey;
    } while (lastEvaluatedKey);

    return items;
  }

  async getAllUserId(lastEvaluatedKey, limit) {
    console.log(`Processing lastEvaluatedKey: ${lastEvaluatedKey}, Limit: ${limit}`);

    const scanParams = {
      TableName: `BPDiary-withdrawal_user_${STAGE}`,
      ProjectionExpression: "user_id",
      Limit: limit,
      ExclusiveStartKey: lastEvaluatedKey ?? undefined,
    };

    const command = new ScanCommand(scanParams);
    return await this.docClient.send(command);
  }
}

// Main execution
async function main() {
  try {
    const args = process.argv.slice(2);
    if (args.length === 0) {
      throw new Error("Missing payload argument");
    }

    const payload = JSON.parse(args[0]);
    console.log("Received payload:", payload);
    const { lastEvaluatedKey, limit } = payload;
    const batchJob = new DeleteUserDataBatch();
    
    const result = await batchJob.getAllUserId(lastEvaluatedKey, limit);
    if (!result.Items || result.Items.length === 0) {
      console.log(`No users found for the given lastEvaluatedKey and limit ${limit}`);
      process.exit(0);
    }

    const userIDList = result.Items.map((item) => ({ user_id: item.user_id }));
    console.log(`Fetched ${userIDList.length} users from withdrawalUser`);

    await batchJob.execute(userIDList);

    console.log("Batch job completed successfully");
    process.exit(0);
  } catch (error) {
    console.error("Error in batch job:", error);
    process.exit(1);
  }
}

main();