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

export class MailsMySQLModel extends MySQLModel<Mails> {
  constructor() {
    // Use simple table name for MySQL (not like DynamoDB with stage suffix)
    const tableName = 'mails';
    super(tableName);
  }
}

