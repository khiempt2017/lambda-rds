/**
* BP-api-serverless
* Created by LoiNV <loinv@vitalify.asia> on 2024/10/11
* Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { APIGatewayProxyEvent, APIGatewayProxyResult, Context } from 'aws-lambda';
import { APIGatewayProxyEventWithCustomBody } from '../types';
import { MiddlewareObj, Request } from '@middy/core';
import Ajv from 'ajv';
import addErrors from 'ajv-errors';
import addFormats from 'ajv-formats';
import { responseAPIGatewayError } from '../shared';
import { INVALID_INPUT_MESSAGE } from '../constants';
import { LoggerService } from '../services';

const validatorMiddleware = (schema): MiddlewareObj<APIGatewayProxyEvent, APIGatewayProxyResult, Error, Context> => {
  // Init
  const ajv = new Ajv({allErrors: true});
  addFormats(ajv);
  addErrors(ajv);
  ajv.addFormat('json-string', {
    validate: (data) => {
      try {
        JSON.parse(data);
        return true; 
      } catch {
        return false;
      }
    },
  });
  const validate = ajv.compile(schema);

  return {
    before: async (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResult, Error, Context>): Promise<void | APIGatewayProxyResult> => {
      if(!validate(request.event.body)){
        const messages = validate.errors?.reduce((messages, curItem) => {
          // For each field
          if(curItem.instancePath !== ''){
            messages[curItem.instancePath] = curItem.message;
            LoggerService.logError(JSON.stringify(messages));
            return messages;
          }

          // For data
          messages['/'] = messages['/'] ? [...messages['/'], curItem.message] : [curItem.message];
          LoggerService.logError(JSON.stringify(messages));
          return messages;
        }, {});
        return responseAPIGatewayError(messages || INVALID_INPUT_MESSAGE);
      }
    },
  };
};

export default validatorMiddleware;
