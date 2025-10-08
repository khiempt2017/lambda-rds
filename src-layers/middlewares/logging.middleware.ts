/**
 * BP-api-serverless
 * Created by LoiNV <loinv@vitalify.asia> on 2024/10/07
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { APIGatewayProxyEvent, APIGatewayProxyResult, Context } from 'aws-lambda';
import { APIGatewayProxyEventWithCustomBody, APIGatewayProxyResultWithCustomBody } from '../types';
import { LoggerService } from '../services';
import { MiddlewareObj, Request } from '@middy/core';

const loggingMiddleware = (): MiddlewareObj<APIGatewayProxyEvent, APIGatewayProxyResult, Error, Context> => {
  let startTime: Date;

  const writeEndLogs = (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResultWithCustomBody<any>, Error, Context>): void => {
    // Logs at the end for an api
    const traceID = request.event.requestContext.requestId;
    const params = request.event.body;
    const screenName = params?.screenName || '';
    const response = request.response || null;
    LoggerService.endWriteLog(traceID, screenName, response);

    // Log all info for an api
    const log = {
      Path: request.event.path,
      Request: {
        headers: request.event.headers,
        body: request.event.body,
        query: request.event.queryStringParameters,
      },
      Response: request.response?.body,
    };
    LoggerService.defaultWriteLog(JSON.stringify(log, null, 2));
  }

  return {
    before: async (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResult, Error, Context>): Promise<void> => {
      // For calculate elapsed time
      startTime = new Date();

      // Logs at the start of an api
      const traceID = request.event.requestContext.requestId;
      const url = request.event.path;
      const params = request.event.body;
      const screenName = params?.screenName || '';
      LoggerService.startWriteLog(traceID, url, screenName, params);
    },

    after: async (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResultWithCustomBody<any>, Error, Context>) : Promise<void> => {
      writeEndLogs(request);
    },

    onError: async (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResultWithCustomBody<any>, Error, Context>) : Promise<void> => {
      writeEndLogs(request);
    },
  };
};

export default loggingMiddleware;
