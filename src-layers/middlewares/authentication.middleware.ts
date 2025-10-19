import { APIGatewayProxyEvent, APIGatewayProxyResult, Context } from 'aws-lambda';
import { 
  UNAUTHORIZED, 
  INVALID_TOKEN, 
  MISSING_TOKEN,
  TOKEN_EXPIRED
} from '../constants';
import { MiddlewareObj, Request } from '@middy/core';
import { APIGatewayProxyEventWithCustomBody } from '../types';
import { extractTokenFromHeader, verifyToken, JWTPayload } from '../utils/jwt.util';
import { LoggerService } from '../services';
import { responseAPIGatewayError } from '../shared';

// Extend the event type to include user info
export interface AuthenticatedEvent extends APIGatewayProxyEventWithCustomBody<any> {
  user?: JWTPayload;
}

const authenticationMiddleware = (): MiddlewareObj<APIGatewayProxyEvent, APIGatewayProxyResult, Error, Context> => {
  const before = async (
    request: Request<AuthenticatedEvent, APIGatewayProxyResult, Error, Context>
  ): Promise<void | APIGatewayProxyResult> => {
    try {
      const headers = request.event.headers;
      
      // Extract token from Authorization header
      const authHeader = headers['Authorization'] || headers['authorization'];
      
      if (!authHeader) {
        LoggerService.logInfo('Authentication failed: Missing token');
        return responseAPIGatewayError(MISSING_TOKEN, 401);
      }

      const token = extractTokenFromHeader(authHeader);
      
      if (!token) {
        LoggerService.logInfo('Authentication failed: Invalid token format');
        return responseAPIGatewayError(INVALID_TOKEN, 401);
      }

      // Verify token
      const decoded = verifyToken(token);
      
      if (!decoded) {
        LoggerService.logInfo('Authentication failed: Token verification failed');
        return responseAPIGatewayError(INVALID_TOKEN, 401);
      }

      // Check if token is expired
      if (decoded.exp && decoded.exp < Math.floor(Date.now() / 1000)) {
        LoggerService.logInfo('Authentication failed: Token expired');
        return responseAPIGatewayError(TOKEN_EXPIRED, 401);
      }

      // Attach user info to event
      request.event.user = decoded;
      
      LoggerService.logInfo(`Authentication successful for user: ${decoded.email} (ID: ${decoded.userId})`);

    } catch (error: any) {
      LoggerService.logError('Authentication error: ' + (error?.message || 'Unknown error'));
      return responseAPIGatewayError(error.message || UNAUTHORIZED, 401);
    }
  };

  return {
    before,
  };
};

export default authenticationMiddleware;
