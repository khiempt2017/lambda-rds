/**
 * mails-clone.model.ts
 * aws-serverless
 * Created by khiempt <khiempt@vitalify.asia> on 2025/03/20
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { AWSDynamo } from '../db/aws-dynamo';
import { TABLE } from '../../constants';

export type MailsClone = {
  user_id: string;
  allow: number;
  record_id: number;
  nick: string;
  authkey: string;
  created_at: string | null;
  updated_at: string | null;
  is_self: number;
  agree: number;
  email: string | null;
};

export const defaultMailsClone: MailsClone = {
  user_id: '',
  allow: 0,
  record_id: 1,
  nick: '',
  authkey: '',
  created_at: null,
  updated_at: null,
  is_self: 0,
  agree: 0,
  email: null,
};

export class MailsCloneModel extends AWSDynamo<MailsClone> {
  constructor(data?: Partial<MailsClone>) {
    super(TABLE.mailsClone);
    this.data = {
      ...defaultMailsClone,
      ...data,
    };
  }
  data: MailsClone;
}
