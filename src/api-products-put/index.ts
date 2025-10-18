/**
* BP-api-serverless
* Products PUT API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, ProductsMySQLService, CategoriesMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Products PUT API ===');
    
    // Initialize services
    const productsService = new ProductsMySQLService();
    const categoriesService = new CategoriesMySQLService();
    
    const pathParameters = event.pathParameters || {};
    const body = event.body ? JSON.parse(event.body) : {};
    
    const { id } = pathParameters;
    
    LoggerService.logInfo(`Path: ${event.path}, Body: ${JSON.stringify(body)}`);
    
    if (!id) {
      return responseError({
        message: 'Validation error',
        error: 'Product ID is required for update',
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
    
    // Check if category exists (if category_id is being updated)
    if (body.category_id) {
      const category = await categoriesService.findById(body.category_id);
      if (!category) {
        return responseError({
          message: 'Validation error',
          error: `Category with ID ${body.category_id} does not exist`,
        });
      }
    }
    
    // Prepare update data
    const updateData: any = {};
    if (body.name) updateData.name = body.name;
    if (body.category_id) updateData.category_id = parseInt(body.category_id);
    if (body.brand) updateData.brand = body.brand;
    if (body.price) updateData.price = parseFloat(body.price);
    if (body.description !== undefined) updateData.description = body.description;
    if (body.image !== undefined) updateData.image = body.image;
    if (body.processor !== undefined) updateData.processor = body.processor;
    if (body.ram !== undefined) updateData.ram = body.ram;
    if (body.storage !== undefined) updateData.storage = body.storage;
    if (body.screen !== undefined) updateData.screen = body.screen;
    if (body.graphics !== undefined) updateData.graphics = body.graphics;
    if (body.status !== undefined) updateData.status = parseInt(body.status);
    
    LoggerService.logInfo(`Updating product ID: ${id} with data: ${JSON.stringify(updateData)}`);
    
    const result = await productsService.updateProduct(parseInt(id), updateData);
    
    return responseSuccess({
      message: 'Product updated successfully',
      data: {
        affectedRows: result.affectedRows,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Products PUT API: ' + error);
    return responseError({
      message: 'Products PUT operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
