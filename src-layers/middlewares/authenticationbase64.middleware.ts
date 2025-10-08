import { APIGatewayProxyEvent, APIGatewayProxyResult, Context } from 'aws-lambda';
import { WRONG_AUTHENTICATION_MESSAGE, API_KEY } from '../constants';
import { MiddlewareObj, Request } from '@middy/core';
import { APIGatewayProxyEventWithCustomBody } from '../types';

const authenticationBase64Middleware = (): MiddlewareObj<APIGatewayProxyEvent, APIGatewayProxyResult, Error, Context> => {
  return {
    before: async (request: Request<APIGatewayProxyEventWithCustomBody<any>, APIGatewayProxyResult, Error, Context>): Promise<void> => {
      const headers = request.event.headers;

      const token = headers['Authorization'] || headers['authorization'];
      if (!token || token !== API_KEY) {
        throw new Error(WRONG_AUTHENTICATION_MESSAGE);
      }

    },
  };
};

export default authenticationBase64Middleware;
