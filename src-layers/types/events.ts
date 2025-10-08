import { APIGatewayProxyResult, APIGatewayProxyEvent } from 'aws-lambda';

export type APIGatewayProxyEventWithCustomBody<T> = Omit<APIGatewayProxyEvent, 'body'> & {
  body: T;
};

export type APIGatewayProxyResultWithCustomBody<T> = Omit<APIGatewayProxyResult, 'body'> & {
  body: T;
};

export type AuthAPIGatewayProxyEventWithCustomBody<T> = APIGatewayProxyEventWithCustomBody<T> & {
  user: {
    userId: string;
    token: string;
  };
};
