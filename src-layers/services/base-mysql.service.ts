/**
 * base-mysql.service.ts
 * BP-api-serverless
 * Base service for MySQL operations with common CRUD methods
 * Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/07
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { MySQLModel } from '../database/db/mysql-connection';
import { ResultSetHeader } from 'mysql2/promise';

/**
 * Common base functions for all MySQL services
 * All MySQL services should extend this class
 * Provides common CRUD operations that can be reused across all services
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

  // ==================== READ OPERATIONS ====================

  /**
   * Get all records
   * @param limit - Optional limit
   * @param offset - Optional offset
   * @returns Array of all records
   */
  public async findAll(limit?: number, offset?: number): Promise<T[]> {
    return await this.model.findAll(limit, offset);
  }

  /**
   * Find record by ID
   * @param id - Record ID
   * @param idColumn - ID column name (default: 'id')
   * @returns Record or undefined
   */
  public async findById(id: any, idColumn: string = 'id'): Promise<T | undefined> {
    return await this.model.findById(id, idColumn);
  }

  /**
   * Find records by conditions
   * @param conditions - Where conditions
   * @param limit - Optional limit
   * @param offset - Optional offset
   * @returns Array of records
   */
  public async findBy(
    conditions: Record<string, any>,
    limit?: number,
    offset?: number
  ): Promise<T[]> {
    return await this.model.findBy(conditions, limit, offset);
  }

  /**
   * Find one record by conditions
   * @param conditions - Where conditions
   * @returns Record or undefined
   */
  public async findOneBy(conditions: Record<string, any>): Promise<T | undefined> {
    return await this.model.findOneBy(conditions);
  }

  // ==================== CREATE OPERATIONS ====================

  /**
   * Create a new record
   * @param data - Data to insert
   * @returns Insert result with insertId
   */
  public async create(data: Partial<T>): Promise<ResultSetHeader> {
    return await this.model.insert(data);
  }

  /**
   * Batch create multiple records
   * @param dataArray - Array of data to insert
   * @returns Insert result
   */
  public async createMany(dataArray: Partial<T>[]): Promise<ResultSetHeader[]> {
    const results: ResultSetHeader[] = [];
    for (const data of dataArray) {
      const result = await this.model.insert(data);
      results.push(result);
    }
    return results;
  }

  // ==================== UPDATE OPERATIONS ====================

  /**
   * Update record by ID
   * @param id - Record ID
   * @param data - Data to update
   * @param idColumn - ID column name (default: 'id')
   * @returns Update result with affectedRows
   */
  public async updateById(
    id: any,
    data: Partial<T>,
    idColumn: string = 'id'
  ): Promise<ResultSetHeader> {
    return await this.model.update(data, { [idColumn]: id });
  }

  /**
   * Update records by conditions
   * @param data - Data to update
   * @param conditions - Where conditions
   * @returns Update result with affectedRows
   */
  public async updateBy(
    data: Partial<T>,
    conditions: Record<string, any>
  ): Promise<ResultSetHeader> {
    return await this.model.update(data, conditions);
  }

  // ==================== DELETE OPERATIONS ====================

  /**
   * Delete record by ID
   * @param id - Record ID
   * @param idColumn - ID column name (default: 'id')
   * @returns Delete result with affectedRows
   */
  public async deleteById(id: any, idColumn: string = 'id'): Promise<ResultSetHeader> {
    return await this.model.delete({ [idColumn]: id });
  }

  /**
   * Delete records by conditions
   * @param conditions - Where conditions
   * @returns Delete result with affectedRows
   */
  public async deleteBy(conditions: Record<string, any>): Promise<ResultSetHeader> {
    return await this.model.delete(conditions);
  }

  // ==================== COUNT & CHECK OPERATIONS ====================

  /**
   * Count all records
   * @param conditions - Optional where conditions
   * @returns Number of records
   */
  public async count(conditions?: Record<string, any>): Promise<number> {
    return await this.model.count(conditions);
  }

  /**
   * Check if record exists
   * @param conditions - Where conditions
   * @returns true if exists
   */
  public async exists(conditions: Record<string, any>): Promise<boolean> {
    return await this.model.exists(conditions);
  }

  // ==================== CUSTOM QUERY OPERATIONS ====================

  /**
   * Execute custom query
   * @param sql - SQL query
   * @param params - Query parameters
   * @returns Query results
   */
  public async query<R = any>(sql: string, params?: any[]): Promise<R[]> {
    return await this.model.query<R>(sql, params);
  }

  /**
   * Execute custom query and return first result
   * @param sql - SQL query
   * @param params - Query parameters
   * @returns First row or undefined
   */
  public async queryOne<R = any>(sql: string, params?: any[]): Promise<R | undefined> {
    return await this.model.queryOne<R>(sql, params);
  }
}

