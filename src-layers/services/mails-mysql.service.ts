/**
 * mails-mysql.service.ts
 * BP-api-serverless
 * Service layer for Mails MySQL operations
 * Created by KhiemPT <KhiemPT@vitalify.asia> on 2025/10/07
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { Mails, MailsMySQLModel, MailsData } from '../database/models/mails-mysql.model';
import { BaseMySQLService } from './base-mysql.service';
import { IS_SELF_STATUS, NUMERIC } from '../constants/common';
import { ResultSetHeader } from 'mysql2/promise';

export class MailsMySQLService extends BaseMySQLService<Mails> {
  private mailsModel: MailsMySQLModel;

  constructor(mailsModel: MailsMySQLModel = new MailsMySQLModel()) {
    super(mailsModel);
    this.mailsModel = mailsModel;
  }

  /**
   * Check if user is self based on user ID
   * @param userId - The ID of the user
   * @returns The mail record if found, null otherwise
   */
  public async checkUserIsSelf(userId: string): Promise<Mails | null> {
    const mails = await this.mailsModel.findSelfMailsByUserId(userId);
    
    if (!mails || mails.length === 0) {
      return null;
    }

    return mails[0];
  }
}

