/**
 * mails-mysql.model.ts
 * BP-api-serverless
 * MySQL version of Mails model
 * Created on 2025/10/07
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { MySQLModel } from '../db/mysql-connection';
import { ResultSetHeader, RowDataPacket } from 'mysql2/promise';

// Base interface without RowDataPacket
export interface MailsData {
  id?: number;
  user_id: string;
  allow: number | null;
  record_id: number;
  nick: string | null;
  authkey: string;
  created_at: string | null;
  updated_at: string | null;
  is_self: number;
  agree: number;
  email: string | null;
}

// Extended interface with RowDataPacket for MySQL query results
export interface Mails extends MailsData, RowDataPacket {}

export const defaultMails: MailsData = {
  id: undefined,
  user_id: '',
  allow: 0,
  record_id: 1,
  nick: null,
  authkey: '',
  created_at: null,
  updated_at: null,
  is_self: 0,
  agree: 0,
  email: null,
};

/**
 * Mails Model for MySQL
 * Example usage:
 * 
 * // Create instance
 * const mailsModel = new MailsMySQLModel();
 * 
 * // Find by user_id
 * const mails = await mailsModel.findByUserId('user123');
 * 
 * // Create new mail record
 * const result = await mailsModel.createMail({
 *   user_id: 'user123',
 *   record_id: 1,
 *   authkey: 'auth_key_123',
 *   is_self: 1,
 *   agree: 1,
 * });
 * 
 * // Update mail record
 * await mailsModel.updateMail('user123', 1, {
 *   email: 'user@example.com',
 *   allow: 1
 * });
 * 
 * // Delete mail record
 * await mailsModel.deleteMail('user123', 1);
 */
export class MailsMySQLModel extends MySQLModel<Mails> {
  constructor() {
    // Determine table name based on environment stage
    const stage = process.env.STAGE || 'local';
    const tableName = stage === 'local' ? 'mails' : `BPDiary_mails_${stage}`;
    super(tableName);
  }

  /**
   * Find all mails by user_id
   * @param userId User ID
   * @returns Array of mail records
   */
  async findByUserId(userId: string): Promise<Mails[]> {
    return await this.findBy({ user_id: userId });
  }

  /**
   * Find mail by user_id and record_id
   * @param userId User ID
   * @param recordId Record ID
   * @returns Mail record or undefined
   */
  async findByUserAndRecordId(userId: string, recordId: number): Promise<Mails | undefined> {
    return await this.findOneBy({ user_id: userId, record_id: recordId });
  }

  /**
   * Find mail by authkey
   * @param authkey Auth key
   * @returns Mail record or undefined
   */
  async findByAuthkey(authkey: string): Promise<Mails | undefined> {
    return await this.findOneBy({ authkey });
  }

  /**
   * Find mails by user_id and created_at range
   * @param userId User ID
   * @param startDate Start date (ISO string)
   * @param endDate End date (ISO string)
   * @returns Array of mail records
   */
  async findByUserAndDateRange(userId: string, startDate: string, endDate: string): Promise<Mails[]> {
    const sql = `
      SELECT * FROM ${this.tableName}
      WHERE user_id = ? AND created_at BETWEEN ? AND ?
      ORDER BY created_at DESC
    `;
    return await this.query<Mails>(sql, [userId, startDate, endDate]);
  }

  /**
   * Find self mails by user_id (is_self = 1)
   * @param userId User ID
   * @returns Array of self mail records
   */
  async findSelfMailsByUserId(userId: string): Promise<Mails[]> {
    return await this.findBy({ user_id: userId, is_self: 1 });
  }

  /**
   * Create a new mail record
   * @param data Mail data
   * @returns Insert result
   */
  async createMail(data: Omit<MailsData, 'id'>): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const mailData = {
      ...data,
      created_at: data.created_at || now,
      updated_at: data.updated_at || now,
    };
    return await this.insert(mailData as Partial<Mails>);
  }

  /**
   * Update mail record by user_id and record_id
   * @param userId User ID
   * @param recordId Record ID
   * @param data Data to update
   * @returns Update result
   */
  async updateMail(
    userId: string,
    recordId: number,
    data: Partial<Omit<MailsData, 'id' | 'user_id' | 'record_id'>>
  ): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const updateData = {
      ...data,
      updated_at: now,
    };
    return await this.update(
      updateData as Partial<Mails>,
      { user_id: userId, record_id: recordId }
    );
  }

  /**
   * Delete mail record by user_id and record_id
   * @param userId User ID
   * @param recordId Record ID
   * @returns Delete result
   */
  async deleteMail(userId: string, recordId: number): Promise<ResultSetHeader> {
    return await this.delete({ user_id: userId, record_id: recordId });
  }

  /**
   * Delete all mails by user_id
   * @param userId User ID
   * @returns Delete result
   */
  async deleteAllMailsByUserId(userId: string): Promise<ResultSetHeader> {
    return await this.delete({ user_id: userId });
  }

  /**
   * Get the next record_id for a user
   * @param userId User ID
   * @returns Next record_id
   */
  async getNextRecordId(userId: string): Promise<number> {
    const sql = `
      SELECT COALESCE(MAX(record_id), 0) + 1 as next_id
      FROM ${this.tableName}
      WHERE user_id = ?
    `;
    const result = await this.queryOne<any>(sql, [userId]);
    return result?.next_id || 1;
  }

  /**
   * Count mails by user_id
   * @param userId User ID
   * @returns Number of mail records
   */
  async countByUserId(userId: string): Promise<number> {
    return await this.count({ user_id: userId });
  }

  /**
   * Batch insert mail records
   * @param mails Array of mail data
   * @returns Insert result
   */
  async batchInsertMails(mails: Omit<MailsData, 'id'>[]): Promise<ResultSetHeader> {
    if (mails.length === 0) {
      throw new Error('No mails to insert');
    }

    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const columns = Object.keys(mails[0]);
    const placeholders = mails.map(() => `(${columns.map(() => '?').join(', ')})`).join(', ');
    
    const values: any[] = [];
    mails.forEach(mail => {
      columns.forEach(col => {
        const value = (mail as any)[col];
        if (col === 'created_at' || col === 'updated_at') {
          values.push(value || now);
        } else {
          values.push(value);
        }
      });
    });

    const sql = `INSERT INTO ${this.tableName} (${columns.join(', ')}) VALUES ${placeholders}`;
    return await this.mysql.execute(sql, values);
  }
}

