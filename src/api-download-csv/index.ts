/**
* BP-api-serverless
* Download CSV API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/12/15
*/

import { LoggerService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, loggingMiddleware } from '/opt/middlewares';
import { responseAPIGatewayError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Download CSV API ===');
    
    // Create dummy CSV data with 2 rows and 2 columns
    const csvHeader = 'Product Name,Price';
    const csvRow1 = 'iPhone 15 Pro,29999000';
    const csvRow2 = 'Samsung Galaxy S24,24999000';
    
    const csvContent = `${csvHeader}\n${csvRow1}\n${csvRow2}`;
    
    LoggerService.logInfo('CSV generated successfully');
    LoggerService.logInfo(`CSV Content: ${csvContent}`);
    
    // Return CSV as plain text (no base64 encoding to avoid middleware issues)
    return {
      statusCode: 200,
      headers: {
        'Content-Type': 'text/csv; charset=utf-8',
        'Content-Disposition': 'attachment; filename="products.csv"',
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'POST,GET,OPTIONS',
        'Access-Control-Allow-Headers': 'Content-Type,X-Amz-Date,Authorization,X-Api-Key',
      },
      body: csvContent,
      isBase64Encoded: false,
    };
    
  } catch (error) {
    LoggerService.logError('Error in Download CSV API: ' + error);
    return responseAPIGatewayError({
      message: 'CSV download failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    }, 500);
  }
}

// Don't use interceptorMiddleware for binary/CSV responses as it wraps response in JSON
const handler = middy(lambdaHandler)
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
