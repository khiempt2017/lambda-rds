/**
* BP-api-serverless
* Products DELETE API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, ProductsMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Products DELETE API ===');
    
    // Initialize service
    const productsService = new ProductsMySQLService();
    
    const pathParameters = event.pathParameters || {};
    const { id } = pathParameters;
    
    LoggerService.logInfo(`Path: ${event.path}`);
    
    if (!id) {
      return responseError({
        message: 'Validation error',
        error: 'Product ID is required for deletion',
      });
    }
    
    // Check if product exists
    const existingProduct = await productsService.findById(parseInt(id));
    if (!existingProduct) {
      return responseError({
        message: 'Product not found',
        error: `Product with ID ${id} does not exist`,
      });
    }
    
    LoggerService.logInfo(`Deleting product ID: ${id}`);
    
    const result = await productsService.deleteProduct(parseInt(id));
    
    return responseSuccess({
      message: 'Product deleted successfully',
      data: {
        affectedRows: result.affectedRows,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Products DELETE API: ' + error);
    return responseError({
      message: 'Products DELETE operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
