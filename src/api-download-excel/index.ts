/**
* BP-api-serverless
* Download Excel API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/12/15
*/

import { LoggerService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, loggingMiddleware } from '/opt/middlewares';
import { responseAPIGatewayError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Download Excel API ===');
    
    // Create Excel content using XML format (simple SpreadsheetML)
    // This format is compatible with Excel without needing external libraries
    const excelContent = `<?xml version="1.0"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
 <Worksheet ss:Name="Products">
  <Table>
   <Row>
    <Cell><Data ss:Type="String">Product Name</Data></Cell>
    <Cell><Data ss:Type="String">Price</Data></Cell>
    <Cell><Data ss:Type="String">Category</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">電話</Data></Cell>
    <Cell><Data ss:Type="Number">29999000</Data></Cell>
    <Cell><Data ss:Type="String">Phone</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">ラップトップ</Data></Cell>
    <Cell><Data ss:Type="Number">24999000</Data></Cell>
    <Cell><Data ss:Type="String">Phone</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">ラップトップ</Data></Cell>
    <Cell><Data ss:Type="Number">45999000</Data></Cell>
    <Cell><Data ss:Type="String">Laptop</Data></Cell>
   </Row>
  </Table>
 </Worksheet>
</Workbook>`;
    
    LoggerService.logInfo('Excel file generated successfully');
    LoggerService.logInfo(`Excel content size: ${excelContent.length} bytes`);
    
    // Return Excel XML as plain text
    return {
      statusCode: 200,
      headers: {
        'Content-Type': 'application/vnd.ms-excel; charset=utf-8',
        'Content-Disposition': 'attachment; filename="products.xlsx"',
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'POST,GET,OPTIONS',
        'Access-Control-Allow-Headers': 'Content-Type,X-Amz-Date,Authorization,X-Api-Key',
      },
      body: excelContent,
      isBase64Encoded: false,
    };
    
  } catch (error) {
    LoggerService.logError('Error in Download Excel API: ' + error);
    return responseAPIGatewayError({
      message: 'Excel download failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    }, 500);
  }
}

// Don't use interceptorMiddleware for binary/Excel responses as it wraps response in JSON
const handler = middy(lambdaHandler)
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
