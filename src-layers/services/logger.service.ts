/**
 * BP-api-serverless
 * Created by LoiNV <loinv@vitalify.asia> on 2024/10/07
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

export class LoggerService {
  /**
  * Log info with format
  * @param {string} message - logs string
  * @return {void}
  */
  static logInfo(message: string | null): void {
    // With format "Stage: message", example: "Dev: message", "Prod: message"
    console.log(process.env.STAGE + `: ${message}\n`);
  }

  /**
  * Log error with format
  * @param {string} message - logs string
  * @return {void}
  */
  static logError(message: string | null): void {
    // With format "Stage: message", example: "Dev: message", "Prod: message"
    console.error(process.env.STAGE + `: ${message}\n`);
  }

  /**
  * Write a basic logs
  * @param {string} message - Message that is writed into logs
  * @return {void}
  */
  static defaultWriteLog(message: string | null): void {
    // Skip write logs for EU
    if (process.env.APP_REGION === 'EU') {
      return;
    }

    // Write logs
    this.logInfo(message);
  }

  /**
  * Write OGSC logs
  * @param {string} traceID - Trace id from lambda event
  * @param {any} message - Message that is writed into logs
  * @return  {void}
  */
  static ogscWriteLog(traceID: string | null, message: any): void {
    // Skip write logs for EU
    if (process.env.APP_REGION === 'EU') {
      return;
    }

    // Write logs
    this.logInfo(`${traceID} OGSC: ${JSON.stringify(message, null, 2)}`);
  }

  /**
  * Write logs at the start of an api.
  * @param {string} traceID - Trace id from lambda event
  * @param {string} screenName - Screen name from frontend
  * @param {object} params - Params in body of a request
  * @return {void}
  */
  static startWriteLog(traceID: string | null, url: string | null, screenName: string | null, params: object | null): void {
    // Skip wrire logs for EU
    if (process.env.APP_REGION === 'EU') {
      return;
    }

    // Write logs
    this.logInfo(`${traceID} ===== Start: ${screenName} =====`);
    this.logInfo(`${traceID} ${JSON.stringify({ url, params }, null, 2)}`);
  }

  /**
  * Write logs at the end of an api.
  * @param {string} traceID - Trace id from lambda event
  * @param {string} screenName - Screen name from frontend
  * @param {object} message - Message that is writed into logs
  * @return {void}
  */
  static endWriteLog(traceID: string | null, screenName: string | null, message: any): void {
    // Skip write logs for EU
    if (process.env.APP_REGION === 'EU') {
      return;
    }

    // Write logs
    this.logInfo(`${traceID} ${JSON.stringify(message, null, 2)}`);
    this.logInfo(`${traceID} ===== End: ${screenName} =====`);
  }

  /**
  * Write logs that uncompleted.
  * @param {string} traceID - Trace id from lambda event
  * @param {string} url - The url of endpoint that is being called
  * @param {object} message - Message that is writed into logs
  * @return {void}
  */
  static writeUncompletedLog(traceID: string | null, url: string | null, message: any): void {
    // Skip write logs for EU
    if (process.env.APP_REGION === 'EU') {
      return;
    }

    // Write logs
    this.logError(`${traceID} ${url} Uncompleted: ${JSON.stringify(message, null, 2)}`);
  }
}
