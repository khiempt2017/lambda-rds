/**
* BP-api-serverless
* Categories POST API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, CategoriesMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Categories POST API ===');
    
    // Initialize service
    const categoriesService = new CategoriesMySQLService();
    
    const body = event.body ? JSON.parse(event.body) : {};
    
    LoggerService.logInfo(`Body: ${JSON.stringify(body)}`);
    
    // Validate required fields
    if (!body.name) {
      return responseError({
        message: 'Validation error',
        error: 'Field \'name\' is required',
      });
    }
    
    // Check if category name already exists
    const existingCategory = await categoriesService.findCategoryByName(body.name);
    if (existingCategory) {
      return responseError({
        message: 'Validation error',
        error: `Category with name '${body.name}' already exists`,
      });
    }
    
    // Prepare category data
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const categoryData = {
      name: body.name,
      description: body.description || null,
      status: body.status !== undefined ? parseInt(body.status) : 1,
      created_at: now,
      updated_at: now,
    };
    
    LoggerService.logInfo(`Creating new category with data: ${JSON.stringify(categoryData)}`);
    
    const result = await categoriesService.createCategory(categoryData);
    
    return responseSuccess({
      message: 'Category created successfully',
      data: {
        insertId: result.insertId,
        affectedRows: result.affectedRows,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Categories POST API: ' + error);
    return responseError({
      message: 'Categories POST operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
