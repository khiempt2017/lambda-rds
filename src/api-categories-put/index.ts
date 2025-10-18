/**
* BP-api-serverless
* Categories PUT API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, CategoriesMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Categories PUT API ===');
    
    // Initialize service
    const categoriesService = new CategoriesMySQLService();
    
    const pathParameters = event.pathParameters || {};
    const body = event.body ? JSON.parse(event.body) : {};
    
    const { id } = pathParameters;
    
    LoggerService.logInfo(`Path: ${event.path}, Body: ${JSON.stringify(body)}`);
    
    if (!id) {
      return responseError({
        message: 'Validation error',
        error: 'Category ID is required for update',
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
    
    // Check if new name already exists (if name is being updated)
    if (body.name && body.name !== existingCategory.name) {
      const categoryWithSameName = await categoriesService.findCategoryByName(body.name);
      if (categoryWithSameName) {
        return responseError({
          message: 'Validation error',
          error: `Category with name '${body.name}' already exists`,
        });
      }
    }
    
    // Prepare update data
    const updateData: any = {};
    if (body.name) updateData.name = body.name;
    if (body.description !== undefined) updateData.description = body.description;
    if (body.status !== undefined) updateData.status = parseInt(body.status);
    
    LoggerService.logInfo(`Updating category ID: ${id} with data: ${JSON.stringify(updateData)}`);
    
    const result = await categoriesService.updateCategory(parseInt(id), updateData);
    
    return responseSuccess({
      message: 'Category updated successfully',
      data: {
        affectedRows: result.affectedRows,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Categories PUT API: ' + error);
    return responseError({
      message: 'Categories PUT operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
