/**
* BP-api-serverless
* Created by LoiNV <loinv@vitalify.asia> on 2024/10/09
* Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { LoggerService, MailsMySQLService } from '/opt/services';
import { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware } from '/opt/middlewares';
import { responseSuccess, responseError } from '/opt/shared';
import { APIGatewayProxyEvent, APIGatewayProxyResult } from 'aws-lambda';

async function lambdaHandler(event: APIGatewayProxyEvent): Promise<APIGatewayProxyResult> {
  try {
    LoggerService.logInfo('=== MySQL Demo: Common Methods (create, findBy, findAll) ===');
    
    // Initialize service
    const mailsService = new MailsMySQLService();
    const testUserId = 'demo_user_' + Date.now();
    
    // 1. CREATE - Sử dụng method create() từ BaseMySQLService
    LoggerService.logInfo('1. Testing CREATE method...');
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const createResult = await mailsService.create({
      user_id: testUserId,
      record_id: 1,
      authkey: 'auth_key_' + Date.now(),
      is_self: 1,
      agree: 1,
      email: 'demo@example.com',
      allow: 1,
      nick: 'Demo User',
      created_at: now,
      updated_at: now,
    } as any);
    LoggerService.logInfo(`✓ Created! Insert ID: ${createResult.insertId}`);
    
    // 2. FIND BY - Sử dụng method findBy() từ BaseMySQLService
    LoggerService.logInfo('2. Testing FIND BY method...');
    const mailsByUser = await mailsService.findBy({ user_id: testUserId });
    LoggerService.logInfo(`✓ Found ${mailsByUser.length} mails for user: ${testUserId}`);
    
    // 3. FIND BY với nhiều conditions
    LoggerService.logInfo('3. Testing FIND BY with multiple conditions...');
    const selfMails = await mailsService.findBy({ 
      user_id: testUserId, 
      is_self: 1 
    });
    LoggerService.logInfo(`✓ Found ${selfMails.length} self mails`);
    
    // 4. FIND ALL - Sử dụng method findAll() từ BaseMySQLService
    LoggerService.logInfo('4. Testing FIND ALL method...');
    const allMails = await mailsService.findAll(10, 0);
    LoggerService.logInfo(`✓ Found ${allMails.length} total mails (limit 10)`);
    
    // 5. Response with all results
    return responseSuccess({
      message: 'MySQL Common Methods Test Completed Successfully',
      methods_tested: ['create', 'findBy', 'findAll'],
      data: {
        // Result from CREATE
        created: {
          insertId: createResult.insertId,
          affectedRows: createResult.affectedRows,
          user_id: testUserId,
        },
        // Result from FIND BY (single condition)
        findByUser: {
          count: mailsByUser.length,
          mails: mailsByUser.map(m => ({
            id: m.id,
            user_id: m.user_id,
            email: m.email,
            nick: m.nick,
          })),
        },
        // Result from FIND BY (multiple conditions)
        findBySelfMails: {
          count: selfMails.length,
          mails: selfMails.map(m => ({
            id: m.id,
            is_self: m.is_self,
          })),
        },
        // Result from FIND ALL
        findAll: {
          count: allMails.length,
          mails: allMails.map(m => ({
            id: m.id,
            user_id: m.user_id,
            email: m.email,
            created_at: m.created_at,
          })),
        },
      },
    });
    
  } catch (error) {
    LoggerService.logError('Error in MySQL demo: ' + error);
    return responseError({
      message: 'MySQL operation failed',
      error: error instanceof Error ? error.message : 'Unknown error',
    });
  }
}

const handler = middy(lambdaHandler)
  .use(interceptorMiddleware())
  .use(loggingMiddleware())
  .use(doNotWaitForEmptyEventLoop({ runOnError: true }))

export { handler };
