/**
* BP-api-serverless
* Created by LoiNV <loinv@vitalify.asia> on 2024/10/08
* Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
*/

import { APIGatewayProxyResult } from "aws-lambda";
import { APIGatewayProxyResultWithCustomBody } from "../types";
import { LoggerService } from "../services";

// CORS Configuration
const corsHeaders = {
  "Access-Control-Allow-Origin": "*",
  "Access-Control-Allow-Methods": "POST,GET,OPTIONS",
  "Access-Control-Allow-Headers": "Content-Type,X-Amz-Date,Authorization,X-Api-Key",
};
/**
 * The function to response success in Lambda Handler
 * @param {object} response - Object contains data
 * @param {number} statusCode - Http status code
 * @return {Response} - Response pattern
 */
export const responseSuccess = (response: object, statusCode = 200): APIGatewayProxyResultWithCustomBody<any> => {
  return {
    statusCode: statusCode,
    headers: { ...corsHeaders },
    body: {
      success: true,
      ...response,
    },
  };
};

/**
 * The function to response success for API Gateway
 * @param {object} response - Object contains data
 * @param {number} statusCode - Http status code
 * @return {Response} - Response pattern
 */
export const responseAPIGatewaySuccess = (response: object, statusCode = 200): APIGatewayProxyResult => {
  return {
    statusCode: statusCode,
    headers: { ...corsHeaders },
    body: JSON.stringify({
      success: true,
      ...response,
    }),
  };
};

/**
 * The function to response error in Lambda Handler
 * @param {object} err - Error message
 * @param {number} statusCode - Http status code
 * @return {Response} - Response pattern
 */
export const responseError = (err: string | object, statusCode = 200): APIGatewayProxyResultWithCustomBody<any> => {
  LoggerService.logError(JSON.stringify(err));
  return {
    statusCode: statusCode,
    headers: { ...corsHeaders },
    body: {
      success: false,
      message: err,
    },
  };
};

/**
 * The function to response error for API Gateway
 * @param {object} err - Error message
 * @param {number} statusCode - Http status code
 * @return {Response} - Response pattern
 */
export const responseAPIGatewayError = (err: string | object, statusCode = 200): APIGatewayProxyResult => {
  LoggerService.logError(JSON.stringify(err));
  return {
    statusCode: statusCode,
    headers: { ...corsHeaders },
    body: JSON.stringify({
      success: false,
      message: err,
    }),
  };
};

