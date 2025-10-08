/**
* BP-api-serverless
* Created by LoiNV <loinv@vitalify.asia> on 2024/10/08
* Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { MiddlewareObj, Request } from "@middy/core";
import { APIGatewayProxyEvent, APIGatewayProxyResult, Context } from "aws-lambda";
import { APIGatewayProxyEventWithCustomBody, APIGatewayProxyResultWithCustomBody } from "../types";
import { LoggerService } from "../services";
import { INVALID_JSON_MESSAGE, SERVER_ERROR_MESSAGE } from "../constants";
import { responseAPIGatewayError, responseAPIGatewaySuccess } from "../shared";

// This is a high priority middleware, so put this on the top in middleware list.
const interceptorMiddleware = (): MiddlewareObj<APIGatewayProxyEvent, APIGatewayProxyResult, Error, Context> => {
  const before = async (request: Request<APIGatewayProxyEvent, APIGatewayProxyResult, Error, Context>) : Promise<void | APIGatewayProxyResult> => {
    try{
      // Check request
      LoggerService.defaultWriteLog('Check request: ' + JSON.stringify(request));
      // Parse body string to any
      if(request.event.body !== null){
        const body = JSON.parse(request.event.body);
        LoggerService.defaultWriteLog('Body: ' + JSON.stringify(body));
        request.event = request.event as APIGatewayProxyEventWithCustomBody<any>;
        request.event.body = body;
      }
    }
    catch(error: any){
      // Log error
      const message = error?.message || INVALID_JSON_MESSAGE;
      LoggerService.defaultWriteLog('Server Error: ' + message);

      // Stop execution flow and return to API Gateway
      return responseAPIGatewayError(message);
    }
  };

  const after = async (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResultWithCustomBody<any>, Error, Context>) : Promise<APIGatewayProxyResult> => {
    // Stop execution flow and return to API Gateway
    return responseAPIGatewaySuccess(request.response?.body);
  }

  const onError = async (request: Request): Promise<APIGatewayProxyResult> => {
    // Log error
    const message = request.error?.message || SERVER_ERROR_MESSAGE;
    LoggerService.defaultWriteLog('Server Error: ' + message);

    // Stop execution flow and return to API Gateway
    return responseAPIGatewayError(message);
  }

  return {
    before,
    after,
    onError,
  }

}

export default interceptorMiddleware;
