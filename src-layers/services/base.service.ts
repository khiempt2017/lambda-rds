/**
 * base-mysql.service.ts
 * BP-api-serverless
 * Base service for MySQL operations
 * Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/07
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { MySQLModel } from '../database/db/mysql-connection';

/**
 * Common base functions for all MySQL services
 * All MySQL services should extend this class
 */
export class BaseMySQLService<T = any> {
  protected readonly model: MySQLModel<T>;

  protected constructor(model: MySQLModel<T>) {
    this.model = model;
  }

  /**
   * Get table name
   * @returns Table name
   */
  public getTableName(): string {
    return this.model.getTableName();
  }
}
