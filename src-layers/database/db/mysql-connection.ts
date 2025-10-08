/**
 * mysql-connection.ts
 * BP-api-serverless
 * MySQL Database Connection and Query Handler
 * Created on 2025/10/07
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import mysql, { Pool, PoolConnection, RowDataPacket, ResultSetHeader, FieldPacket, OkPacket } from 'mysql2/promise';
import { LoggerService } from '/opt/services';

// MySQL Configuration Interface
export interface MySQLConfig {
  host: string;
  port: number;
  database: string;
  user: string;
  password: string;
  connectionLimit?: number;
  timezone?: string;
  waitForConnections?: boolean;
  queueLimit?: number;
  enableKeepAlive?: boolean;
  keepAliveInitialDelay?: number;
}

// Query result types
export type QueryResult<T = any> = [T[], FieldPacket[]];
export type ExecuteResult = [ResultSetHeader, FieldPacket[]];

// For singleton pattern
let pool: Pool | null = null;

/**
 * MySQL Connection Manager
 * Manages connection pool and provides query methods
 */
export class MySQLConnection {
  private pool: Pool;
  private static instance: MySQLConnection | null = null;

  private constructor() {
    const config: MySQLConfig = {
      host: process.env.MYSQL_HOST || 'localhost',
      port: parseInt(process.env.MYSQL_PORT || '3306'),
      database: process.env.MYSQL_DATABASE || 'bp_manager',
      user: process.env.MYSQL_USER || 'root',
      password: process.env.MYSQL_PASSWORD || '',
      connectionLimit: 10,
      // timezone: process.env.MYSQL_TIMEZONE || '+09:00',
      waitForConnections: true,
      queueLimit: 0,
      enableKeepAlive: true,
      keepAliveInitialDelay: 0,
    };

    // Create connection pool (singleton pattern)
    if (!pool) {
      pool = mysql.createPool(config);
      LoggerService.logInfo('MySQL connection pool created');
    }

    this.pool = pool;
  }

  /**
   * Get singleton instance of MySQLConnection
   * @returns MySQLConnection instance
   */
  public static getInstance(): MySQLConnection {
    if (!MySQLConnection.instance) {
      MySQLConnection.instance = new MySQLConnection();
    }
    return MySQLConnection.instance;
  }

  /**
   * Get connection pool
   * @returns Pool instance
   */
  public getPool(): Pool {
    return this.pool;
  }

  /**
   * Get a connection from the pool
   * @returns PoolConnection
   */
  public async getConnection(): Promise<PoolConnection> {
    try {
      const connection = await this.pool.getConnection();
      return connection;
    } catch (error) {
      LoggerService.logError(`Error getting connection from pool: ${error}`);
      throw error;
    }
  }

  /**
   * Execute a query
   * @param sql SQL query string
   * @param params Query parameters
   * @returns Query results
   */
  public async query<T extends RowDataPacket[] = RowDataPacket[]>(
    sql: string,
    params?: any[]
  ): Promise<T> {
    try {
      const [rows] = await this.pool.query<T>(sql, params);
      return rows;
    } catch (error) {
      LoggerService.logError(`Query error: ${error}\nSQL: ${sql}\nParams: ${JSON.stringify(params)}`);
      throw error;
    }
  }

  /**
   * Execute a query and return first row
   * @param sql SQL query string
   * @param params Query parameters
   * @returns First row or undefined
   */
  public async queryOne<T extends RowDataPacket = RowDataPacket>(
    sql: string,
    params?: any[]
  ): Promise<T | undefined> {
    try {
      const [rows] = await this.pool.query<T[]>(sql, params);
      return rows[0];
    } catch (error) {
      LoggerService.logError(`QueryOne error: ${error}\nSQL: ${sql}\nParams: ${JSON.stringify(params)}`);
      throw error;
    }
  }

  /**
   * Execute an insert/update/delete query
   * @param sql SQL query string
   * @param params Query parameters
   * @returns ResultSetHeader with affectedRows, insertId, etc.
   */
  public async execute(sql: string, params?: any[]): Promise<ResultSetHeader> {
    try {
      const [result] = await this.pool.execute<ResultSetHeader>(sql, params);
      return result;
    } catch (error) {
      LoggerService.logError(`Execute error: ${error}\nSQL: ${sql}\nParams: ${JSON.stringify(params)}`);
      throw error;
    }
  }

  /**
   * Begin a transaction
   * @returns PoolConnection with transaction started
   */
  public async beginTransaction(): Promise<PoolConnection> {
    const connection = await this.getConnection();
    await connection.beginTransaction();
    return connection;
  }

  /**
   * Commit a transaction
   * @param connection PoolConnection
   */
  public async commit(connection: PoolConnection): Promise<void> {
    try {
      await connection.commit();
      connection.release();
    } catch (error) {
      LoggerService.logError(`Commit error: ${error}`);
      throw error;
    }
  }

  /**
   * Rollback a transaction
   * @param connection PoolConnection
   */
  public async rollback(connection: PoolConnection): Promise<void> {
    try {
      await connection.rollback();
      connection.release();
    } catch (error) {
      LoggerService.logError(`Rollback error: ${error}`);
      throw error;
    }
  }

  /**
   * Execute multiple queries in a transaction
   * @param callback Function that receives connection and executes queries
   * @returns Result from callback
   */
  public async transaction<T>(callback: (connection: PoolConnection) => Promise<T>): Promise<T> {
    const connection = await this.beginTransaction();
    try {
      const result = await callback(connection);
      await this.commit(connection);
      return result;
    } catch (error) {
      await this.rollback(connection);
      throw error;
    }
  }

  /**
   * Close all connections in the pool
   */
  public async close(): Promise<void> {
    try {
      await this.pool.end();
      pool = null;
      MySQLConnection.instance = null;
      LoggerService.logInfo('MySQL connection pool closed');
    } catch (error) {
      LoggerService.logError(`Error closing connection pool: ${error}`);
      throw error;
    }
  }

  /**
   * Test database connection
   * @returns true if connection is successful
   */
  public async testConnection(): Promise<boolean> {
    try {
      const [rows] = await this.pool.query('SELECT 1 as test');
      return Array.isArray(rows) && rows.length > 0;
    } catch (error) {
      LoggerService.logError(`Connection test failed: ${error}`);
      return false;
    }
  }
}

/**
 * Get MySQL connection instance (shorthand)
 * @returns MySQLConnection instance
 */
export const getMySQL = (): MySQLConnection => {
  return MySQLConnection.getInstance();
};

/**
 * Abstract base class for MySQL models
 * Similar to AWSDynamo but for MySQL
 */
export abstract class MySQLModel<T = any> {
  protected readonly tableName: string;
  protected mysql: MySQLConnection;

  protected constructor(tableName: string) {
    this.tableName = tableName;
    this.mysql = MySQLConnection.getInstance();
  }

  /**
   * Get table name
   */
  public getTableName(): string {
    return this.tableName;
  }

  /**
   * Find by ID
   * @param id Primary key value
   * @param idColumn Primary key column name (default: 'id')
   * @returns Record or undefined
   */
  public async findById(id: any, idColumn: string = 'id'): Promise<T | undefined> {
    const sql = `SELECT * FROM ${this.tableName} WHERE ${idColumn} = ? LIMIT 1`;
    return await this.mysql.queryOne<any>(sql, [id]);
  }

  /**
   * Find all records
   * @param limit Optional limit
   * @param offset Optional offset
   * @returns Array of records
   */
  public async findAll(limit?: number, offset?: number): Promise<T[]> {
    let sql = `SELECT * FROM ${this.tableName}`;
    const params: any[] = [];

    if (limit !== undefined) {
      sql += ' LIMIT ?';
      params.push(limit);

      if (offset !== undefined) {
        sql += ' OFFSET ?';
        params.push(offset);
      }
    }

    return await this.mysql.query<any[]>(sql, params);
  }

  /**
   * Find records by conditions
   * @param conditions Object with column: value pairs
   * @param limit Optional limit
   * @param offset Optional offset
   * @returns Array of records
   */
  public async findBy(conditions: Record<string, any>, limit?: number, offset?: number): Promise<T[]> {
    const keys = Object.keys(conditions);
    const values = Object.values(conditions);

    const whereClause = keys.map(key => `${key} = ?`).join(' AND ');
    let sql = `SELECT * FROM ${this.tableName} WHERE ${whereClause}`;

    if (limit !== undefined) {
      sql += ' LIMIT ?';
      values.push(limit);

      if (offset !== undefined) {
        sql += ' OFFSET ?';
        values.push(offset);
      }
    }

    return await this.mysql.query<any[]>(sql, values);
  }

  /**
   * Find one record by conditions
   * @param conditions Object with column: value pairs
   * @returns Record or undefined
   */
  public async findOneBy(conditions: Record<string, any>): Promise<T | undefined> {
    const results = await this.findBy(conditions, 1);
    return results[0];
  }

  /**
   * Insert a record
   * @param data Data to insert
   * @returns Insert result with insertId
   */
  public async insert(data: Partial<T>): Promise<ResultSetHeader> {
    const keys = Object.keys(data);
    const values = Object.values(data);

    const columns = keys.join(', ');
    const placeholders = keys.map(() => '?').join(', ');

    const sql = `INSERT INTO ${this.tableName} (${columns}) VALUES (${placeholders})`;
    return await this.mysql.execute(sql, values);
  }

  /**
   * Update records
   * @param data Data to update
   * @param conditions Where conditions
   * @returns Update result with affectedRows
   */
  public async update(data: Partial<T>, conditions: Record<string, any>): Promise<ResultSetHeader> {
    const dataKeys = Object.keys(data);
    const dataValues = Object.values(data);

    const conditionKeys = Object.keys(conditions);
    const conditionValues = Object.values(conditions);

    const setClause = dataKeys.map(key => `${key} = ?`).join(', ');
    const whereClause = conditionKeys.map(key => `${key} = ?`).join(' AND ');

    const sql = `UPDATE ${this.tableName} SET ${setClause} WHERE ${whereClause}`;
    return await this.mysql.execute(sql, [...dataValues, ...conditionValues]);
  }

  /**
   * Delete records
   * @param conditions Where conditions
   * @returns Delete result with affectedRows
   */
  public async delete(conditions: Record<string, any>): Promise<ResultSetHeader> {
    const keys = Object.keys(conditions);
    const values = Object.values(conditions);

    const whereClause = keys.map(key => `${key} = ?`).join(' AND ');
    const sql = `DELETE FROM ${this.tableName} WHERE ${whereClause}`;

    return await this.mysql.execute(sql, values);
  }

  /**
   * Count records
   * @param conditions Optional where conditions
   * @returns Number of records
   */
  public async count(conditions?: Record<string, any>): Promise<number> {
    let sql = `SELECT COUNT(*) as count FROM ${this.tableName}`;
    const values: any[] = [];

    if (conditions && Object.keys(conditions).length > 0) {
      const keys = Object.keys(conditions);
      const whereClause = keys.map(key => `${key} = ?`).join(' AND ');
      sql += ` WHERE ${whereClause}`;
      values.push(...Object.values(conditions));
    }

    const result = await this.mysql.queryOne<any>(sql, values);
    return result?.count || 0;
  }

  /**
   * Check if record exists
   * @param conditions Where conditions
   * @returns true if exists
   */
  public async exists(conditions: Record<string, any>): Promise<boolean> {
    const count = await this.count(conditions);
    return count > 0;
  }

  /**
   * Execute custom query
   * @param sql SQL query
   * @param params Query parameters
   * @returns Query results
   */
  public async query<R = any>(sql: string, params?: any[]): Promise<R[]> {
    return await this.mysql.query<any[]>(sql, params);
  }

  /**
   * Execute custom query and return first result
   * @param sql SQL query
   * @param params Query parameters
   * @returns First row or undefined
   */
  public async queryOne<R = any>(sql: string, params?: any[]): Promise<R | undefined> {
    return await this.mysql.queryOne<any>(sql, params);
  }
}

