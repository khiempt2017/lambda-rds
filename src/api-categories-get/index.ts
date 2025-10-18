/**
* BP-api-serverless
* Categories GET API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, CategoriesMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Categories GET API ===');
    
    // Initialize service
    const categoriesService = new CategoriesMySQLService();
    
    const pathParameters = event.pathParameters || {};
    const queryStringParameters = event.queryStringParameters || {};
    
    const { id } = pathParameters;
    const { status, limit, offset } = queryStringParameters;
    
    LoggerService.logInfo(`Path: ${event.path}, Query: ${JSON.stringify(queryStringParameters)}`);
    
    if (id) {
      // Get single category by ID
      LoggerService.logInfo(`Getting category by ID: ${id}`);
      const category = await categoriesService.findById(parseInt(id));
      
      if (!category) {
        return responseError({
          message: 'Category not found',
          error: `Category with ID ${id} does not exist`,
        });
      }
      
      return responseSuccess({
        message: 'Category retrieved successfully',
        data: category,
      });
    }
    
    // Get multiple categories with filters
    LoggerService.logInfo('Getting categories with filters');
    
    // Build dynamic WHERE conditions
    const whereConditions: string[] = [];
    const params: any[] = [];
    
    if (status !== undefined) {
      whereConditions.push('status = ?');
      params.push(parseInt(status));
    }
    
    // Apply limit and offset
    const limitNum = limit ? parseInt(limit) : undefined;
    const offsetNum = offset ? parseInt(offset) : 0;
    
    // Use common get method with dynamic conditions
    const categories = await categoriesService.get(whereConditions, limitNum, offsetNum, params);
    
    return responseSuccess({
      message: 'Categories retrieved successfully',
      data: {
        categories,
        count: categories.length,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Categories GET API: ' + error);
    return responseError({
      message: 'Categories GET operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
