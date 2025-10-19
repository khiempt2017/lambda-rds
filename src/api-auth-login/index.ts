/**
 * API Auth Login
 * POST /api/auth/login
 * Created: 2025/10/19
 */

import { middy } from '/opt/middlewares';
import { APIGatewayProxyEvent, APIGatewayProxyResult, Context } from 'aws-lambda';
import { 
  UsersMySQLService,
  LoggerService
} from '/opt/services';
import { 
  INVALID_CREDENTIALS,
  LOGIN_SUCCESS,
  INVALID_INPUT_MESSAGE
} from '/opt/constants';
import interceptorMiddleware from '/opt/middlewares/interceptor.middleware';
import { generateToken } from '/opt/utils/jwt.util';

const handler = async (
  event: APIGatewayProxyEvent,
  context: Context
): Promise<APIGatewayProxyResult> => {
  LoggerService.logInfo('POST /api/auth/login - Login request');
  
  try {
    const usersService = new UsersMySQLService();
    
    // Parse body
    const body = typeof event.body === 'string' ? JSON.parse(event.body) : event.body;
    const { email, password } = body;

    // Validate input
    if (!email || !password) {
      return {
        statusCode: 400,
        body: {
          success: false,
          message: INVALID_INPUT_MESSAGE,
          error: 'Email and password are required'
        }
      } as any;
    }

    LoggerService.logInfo(`Login attempt for email: ${email}`);

    // Build dynamic WHERE conditions
    const whereConditions: string[] = ['email = ?', 'password = ?', 'status = ?'];
    const params: any[] = [email, password, 1];

    // Authenticate user
    const user = await usersService.authenticate(whereConditions, params);

    if (!user) {
      LoggerService.logInfo(`Login failed for email: ${email}`);
      return {
        statusCode: 401,
        body: {
          success: false,
          message: INVALID_CREDENTIALS
        }
      } as any;
    }

    LoggerService.logInfo(`Login successful for user ID: ${user.id}`);

    // Generate JWT token
    const access_token = generateToken({
      userId: user.id,
      email: user.email,
      role: user.role
    });

    // Return success response with access_token and user info
    return {
      statusCode: 200,
      body: {
        success: true,
        message: LOGIN_SUCCESS,
        data: {
          access_token,
          user: {
            id: user.id,
            email: user.email,
            full_name: user.full_name,
            role: user.role,
            status: user.status
          }
        }
      }
    } as any;

  } catch (error: any) {
    LoggerService.logError('Login error: ' + error);
    return {
      statusCode: 500,
      body: {
        success: false,
        message: 'Internal server error',
        error: error.message
      }
    } as any;
  }
};

export const main = middy(handler)
  .use(interceptorMiddleware());

