/**
* BP-api-serverless
* Products POST API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, ProductsMySQLService, CategoriesMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Products POST API ===');
    
    // Initialize services
    const productsService = new ProductsMySQLService();
    const categoriesService = new CategoriesMySQLService();
    
    const body = event.body ? JSON.parse(event.body) : {};
    
    LoggerService.logInfo(`Body: ${JSON.stringify(body)}`);
    
    // Validate required fields
    const requiredFields = ['name', 'category_id', 'brand', 'price'];
    for (const field of requiredFields) {
      if (!body[field]) {
        return responseError({
          message: 'Validation error',
          error: `Field '${field}' is required`,
        });
      }
    }
    
    // Check if category exists
    const category = await categoriesService.findById(body.category_id);
    if (!category) {
      return responseError({
        message: 'Validation error',
        error: `Category with ID ${body.category_id} does not exist`,
      });
    }
    
    // Prepare product data
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const productData = {
      name: body.name,
      category_id: parseInt(body.category_id),
      brand: body.brand,
      price: parseFloat(body.price),
      description: body.description || null,
      image: body.image || null,
      processor: body.processor || null,
      ram: body.ram || null,
      storage: body.storage || null,
      screen: body.screen || null,
      graphics: body.graphics || null,
      status: body.status !== undefined ? parseInt(body.status) : 1,
      created_at: now,
      updated_at: now,
    };
    
    LoggerService.logInfo(`Creating new product with data: ${JSON.stringify(productData)}`);
    
    const result = await productsService.createProduct(productData);
    
    return responseSuccess({
      message: 'Product created successfully',
      data: {
        insertId: result.insertId,
        affectedRows: result.affectedRows,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Products POST API: ' + error);
    return responseError({
      message: 'Products POST operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
