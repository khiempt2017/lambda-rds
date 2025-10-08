/**
 * BP-api-serverless
 * Created by khuongdv <khuongdv@vitalify.asia> on 2024/10/03
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import middy from '@middy/core';
import doNotWaitForEmptyEventLoop from '@middy/do-not-wait-for-empty-event-loop';
import loggingMiddleware from './logging.middleware';
// import authenticationMiddleware from './authentication.middleware';
import interceptorMiddleware from './interceptor.middleware';
import validatorMiddleware from './validator.middleware';
import authenticationBase64Middleware from './authenticationbase64.middleware';

export { middy, doNotWaitForEmptyEventLoop, interceptorMiddleware, loggingMiddleware, validatorMiddleware, authenticationBase64Middleware };
