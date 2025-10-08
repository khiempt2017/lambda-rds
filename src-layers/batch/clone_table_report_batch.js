const { DynamoDBClient } = require('@aws-sdk/client-dynamodb');
const { 
  DynamoDBDocumentClient, 
  QueryCommand,
  PutCommand,
  ScanCommand
} = require('@aws-sdk/lib-dynamodb');

// Constants
const STAGE = process.env.ENVIRONMENT;
const EMAIL_STATUS = {
  UNPROCESSED: 0,
  PROCESSING: 1,
  PROCESSED: 2,
  ERROR: 3
};

class CloneTableReportBatch {
  constructor() {
    this.dynamoClient = new DynamoDBClient({ region: process.env.REGION });
    this.docClient = DynamoDBDocumentClient.from(this.dynamoClient);
  }

  async execute() {
    console.log('Starting clone table report data processing');
    let sendSelfCnt = 0;
    let sendOthrsApprovedCnt = 0;
    let sendOthrsUnapprovedCnt = 0;
    
    try {
      // Define table names
      const regularReportTable = `BPDiary-regular_report_${STAGE}`;
      const regularReportCloneTable = `BPDiary-regular_report_clone_${STAGE}`;
      const mailsTable = `BPDiary-mails_${STAGE}`;
      const mailsCloneTable = `BPDiary-mails_clone_${STAGE}`;

      // Verify all required tables exist
      const tables = [regularReportTable, regularReportCloneTable, mailsTable, mailsCloneTable];
      for (const table of tables) {
        try {
          await this.docClient.send(new ScanCommand({
            TableName: table,
            Limit: 1
          }));
        } catch (error) {
          if (error.name === 'ResourceNotFoundException') {
            console.error(`Error: Required table ${table} does not exist`);
            return {
              success: false,
              message: `Required table ${table} does not exist`
            };
          }
          throw error;
        }
      }

      // Clone data from regular_report to regular_report_clone
      console.log('Copying data from regular_report to regular_report_clone');
      
      // Get eligible regular report users (auto_send=1, logout_flg=0)
      let lastEvaluatedKey = undefined;
      let totalCopied = 0;
      let useOnCnt = 0;
      
      do {
        // Use query instead of scan for better performance
        const queryParams = {
          TableName: regularReportTable,
          IndexName: 'logout_flg_auto_send_index',
          KeyConditionExpression: 'logout_flg = :logoutFlg AND auto_send = :autoSend',
          ExpressionAttributeValues: {
            ':logoutFlg': 0,
            ':autoSend': 1
          },
          ProjectionExpression: 'user_id, auto_send, logout_flg, refresh_token, login_flg, message_flg, init_tutorial, introduction, email_slide_tutorial, first_time_set_nickname, created_at, updated_at, token_tmp',
          ExclusiveStartKey: lastEvaluatedKey
        };
        
        const command = new QueryCommand(queryParams);
        const regularReportResult = await this.docClient.send(command);
        
        useOnCnt += regularReportResult.Items?.length || 0;
        
        // For each user, check if they have valid mail entries
        if (regularReportResult.Items && regularReportResult.Items.length > 0) {
          for (const user of regularReportResult.Items) {
            try {
              // Check if user has valid mail entries
              let userHasValidMails = false;
              // Store valid emails to reuse later
              let validMailsForUser = [];

              //Query for self mail
              const mailsExistSelfMail = {
                TableName: mailsTable,
                IndexName: 'user_is_self_index',
                KeyConditionExpression: 'user_id = :userId AND is_self = :isSelfTrue',
                FilterExpression: 'allow = :allow',
                ExpressionAttributeValues: {
                  ':userId': user.user_id,
                  ':isSelfTrue': 1,
                  ':allow': 1
                },
                ProjectionExpression: 'user_id, record_id, agree, allow, authkey, created_at, email, is_self, nick, updated_at'
              };

              //Query for other mail
              const mailsExistOtherMail = {
                TableName: mailsTable,
                IndexName: 'user_is_self_index',
                KeyConditionExpression: 'user_id = :userId AND is_self = :isSelfFalse',
                FilterExpression: 'allow = :allow AND agree = :agree AND email <> :emptyEmail',
                ExpressionAttributeValues: {
                  ':userId': user.user_id,
                  ':isSelfFalse': 0,
                  ':allow': 1,
                  ':agree': 1,
                  ':emptyEmail': ''
                },
                ProjectionExpression: 'user_id, record_id, agree, allow, authkey, created_at, email, is_self, nick, updated_at'
              };
              
              const [mailsResult1, mailsResult2] = await Promise.all([
                this.docClient.send(new QueryCommand(mailsExistSelfMail)),
                this.docClient.send(new QueryCommand(mailsExistOtherMail))
              ]);
              
              if (mailsResult1.Items && mailsResult1.Items.length > 0) {
                validMailsForUser = validMailsForUser.concat(mailsResult1.Items);
              }
              if (mailsResult2.Items && mailsResult2.Items.length > 0) {
                validMailsForUser = validMailsForUser.concat(mailsResult2.Items);
              }

              userHasValidMails = validMailsForUser.length > 0;

              // Only copy user if they have valid mail entries
              if (userHasValidMails) {
                // Add status field to user data and add country field with null value
                const userClone = { 
                  ...user, 
                  status: EMAIL_STATUS.UNPROCESSED,
                  country: null
                };
                
                // Write to clone table
                await this.docClient.send(new PutCommand({
                  TableName: regularReportCloneTable,
                  Item: userClone
                }));
                
                totalCopied++;
                
                // Process the already-collected valid mails directly
                console.log(`Processing ${validMailsForUser.length} mails for user: ${user.user_id}`);
                
                // Process all valid mails we've already queried
                for (const mail of validMailsForUser) {
                  // Count statistics
                  if (mail.is_self === 1) {
                    sendSelfCnt++;
                  } else if (mail.agree === 1) {
                    sendOthrsApprovedCnt++;
                  } else {
                    sendOthrsUnapprovedCnt++;
                  }
                  
                  // These mails already match the conditions for copying
                  await this.docClient.send(new PutCommand({
                    TableName: mailsCloneTable,
                    Item: mail
                  }));
                }
              }
            } catch (error) {
              console.error(`Error processing user_id ${user.user_id}:`, error);
              // Continue with next user even if current one fails
              continue;
            }
          }
        }
        
        lastEvaluatedKey = regularReportResult.LastEvaluatedKey;
      } while (lastEvaluatedKey);
      
      console.log(`Copied ${totalCopied} regular report records`);
      console.log('Table cloning completed successfully');
      
      console.log('useOnCnt: ' + useOnCnt);
      console.log('sendSelfCnt: ' + sendSelfCnt);
      console.log('sendOthrsApprovedCnt: ' + sendOthrsApprovedCnt);
      console.log('sendOthrsUnapprovedCnt: ' + sendOthrsUnapprovedCnt);
      
      // Return statistics
      return {
        success: true,
        data: {
          useOnCnt,
          sendSelfCnt,
          sendOthrsApprovedCnt,
          sendOthrsUnapprovedCnt
        }
      };
    } catch (error) {
      console.error(`Error in cloneTableReport: ${error}`);
      return {
        success: false,
        message: `Failed to clone tables: ${error}`
      };
    }
  }
}

// Main execution
async function main() {
  try {
    console.log("Starting clone table report batch job");
    const batchJob = new CloneTableReportBatch();
    const result = await batchJob.execute();
    
    console.log("Batch job completed with result:", JSON.stringify(result));
    process.exit(0);
  } catch (error) {
    console.error("Error in batch job:", error);
    process.exit(1);
  }
}

main();
