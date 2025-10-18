/**
* BP-api-serverless
* Categories DELETE API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, CategoriesMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Categories DELETE API ===');
    
    // Initialize service
    const categoriesService = new CategoriesMySQLService();
    
    const pathParameters = event.pathParameters || {};
    const { id } = pathParameters;
    
    LoggerService.logInfo(`Path: ${event.path}`);
    
    if (!id) {
      return responseError({
        message: 'Validation error',
        error: 'Category ID is required for deletion',
      });
    }
    
    // Check if category exists
    const existingCategory = await categoriesService.findById(parseInt(id));
    if (!existingCategory) {
      return responseError({
        message: 'Category not found',
        error: `Category with ID ${id} does not exist`,
      });
    }
    
    // Check if category can be deleted (no products associated)
    const canDelete = await categoriesService.canDeleteCategory(parseInt(id));
    if (!canDelete) {
      return responseError({
        message: 'Cannot delete category',
        error: 'There are products associated with this category. Please delete or move the products first.',
      });
    }
    
    LoggerService.logInfo(`Deleting category ID: ${id}`);
    
    const result = await categoriesService.deleteCategory(parseInt(id));
    
    return responseSuccess({
      message: 'Category deleted successfully',
      data: {
        affectedRows: result.affectedRows,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Categories DELETE API: ' + error);
    return responseError({
      message: 'Categories DELETE operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
