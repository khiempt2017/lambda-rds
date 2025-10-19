/**
 * users-mysql.service.ts
 * BP-api-serverless
 * Service layer for Users MySQL operations
 * Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/19
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { Users, UsersMySQLModel, UsersData } from '../database/models/users-mysql.model';
import { BaseMySQLService } from './base-mysql.service';

export class UsersMySQLService extends BaseMySQLService<Users> {
  private usersModel: UsersMySQLModel;

  constructor(usersModel: UsersMySQLModel = new UsersMySQLModel()) {
    super(usersModel);
    this.usersModel = usersModel;
  }

  /**
   * Authenticate user with conditions
   * @param whereConditions - Array of WHERE conditions
   * @param params - Array of parameters for conditions
   * @returns User object if found, undefined otherwise
   */
  public async authenticate(whereConditions: string[], params: any[]): Promise<Users | undefined> {
    // Use get() from BaseMySQLService with limit 1
    const users = await this.get(whereConditions, 1, 0, params);
    
    return users && users.length > 0 ? users[0] : undefined;
  }
}
