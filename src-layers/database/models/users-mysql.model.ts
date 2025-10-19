/**
 * users-mysql.model.ts
 * BP-api-serverless
 * MySQL version of Users model
 * Created on 2025/10/19
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { MySQLModel } from '../db/mysql-connection';
import { ResultSetHeader, RowDataPacket } from 'mysql2/promise';

// Base interface without RowDataPacket
export interface UsersData {
  id?: number;
  email: string;
  password: string;
  full_name: string;
  role: string;
  status: number;
  created_at?: Date | string;
  updated_at?: Date | string;
}

// Extended interface with RowDataPacket for query results
export interface Users extends UsersData, RowDataPacket {
  id: number;
  created_at: Date | string;
  updated_at: Date | string;
}

export class UsersMySQLModel extends MySQLModel<Users> {
  constructor() {
    super('users');
  }
}

export default UsersMySQLModel;
