/**
* BP-api-serverless
* Products GET API
* Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/18
* Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, ProductsMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== Products GET API ===');
    
    // Initialize service
    const productsService = new ProductsMySQLService();
    
    const pathParameters = event.pathParameters || {};
    const queryStringParameters = event.queryStringParameters || {};
    
    const { id } = pathParameters;
    const { category_id, brand, search, status, limit, offset } = queryStringParameters;
    
    LoggerService.logInfo(`Path: ${event.path}, Query: ${JSON.stringify(queryStringParameters)}`);
    
    if (id) {
      // Get single product by ID
      LoggerService.logInfo(`Getting product by ID: ${id}`);
      const product = await productsService.findById(parseInt(id));
      
      if (!product) {
        return responseError({
          message: 'Product not found',
          error: `Product with ID ${id} does not exist`,
        });
      }
      
      return responseSuccess({
        message: 'Product retrieved successfully',
        data: product,
      });
    }
    
    // Get multiple products with filters
    LoggerService.logInfo('Getting products with filters');
    
    // Build dynamic WHERE conditions
    const whereConditions: string[] = [];
    const params: any[] = [];
    
    if (category_id) {
      whereConditions.push('p.category_id = ?');
      params.push(parseInt(category_id));
    }
    
    if (brand) {
      whereConditions.push('p.brand = ?');
      params.push(brand);
    }
    
    if (status !== undefined && status !== null) {
      whereConditions.push('p.status = ?');
      params.push(parseInt(status));
    }
    
    if (search) {
      whereConditions.push('(p.name LIKE ? OR p.description LIKE ?)');
      const searchPattern = `%${search}%`;
      params.push(searchPattern, searchPattern);
    }
    
    // Build SQL with JOIN
    let sql = `
      SELECT p.*, c.name as category_name, c.description as category_description
      FROM products p
      LEFT JOIN categories c ON p.category_id = c.id
    `;
    
    if (whereConditions.length > 0) {
      sql += ` WHERE ${whereConditions.join(' AND ')}`;
    }
    
    sql += ' ORDER BY p.created_at DESC';
    
    // Apply limit and offset
    const limitNum = limit ? parseInt(limit) : 10;
    const offsetNum = offset ? parseInt(offset) : 0;
    
    sql += ` LIMIT ${limitNum}`;
    if (offsetNum > 0) {
      sql += ` OFFSET ${offsetNum}`;
    }
    
    const products = await productsService.query(sql, params);
    
    return responseSuccess({
      message: 'Products retrieved successfully',
      data: {
        products,
        count: products.length,
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in Products GET API: ' + error);
    return responseError({
      message: 'Products GET operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
