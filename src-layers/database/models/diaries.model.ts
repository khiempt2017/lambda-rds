/**
 * regular-report.model.ts
 * aws-serverless
 * Created by QuiNV <quinv@vitalify.asia> on 2024/10/14
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { AWSDynamo } from '../db/aws-dynamo';
import { TABLE } from '../../constants';

export type Diaries = {
  user_id: string;
  date: string;
  medicine_time1: string | null;
  medicine_time2: string | null;
  medicine_time3: string | null;
  memo_mask: number | null;
  memo_text: string | null;
  created_at: string;
  updated_at: string;
};

export const defaultDiaries: Diaries = {
  user_id: '',
  date: '',
  medicine_time1: null,
  medicine_time2: null,
  medicine_time3: null,
  memo_mask: null,
  memo_text: null,
  created_at: new Date().toISOString(),
  updated_at: new Date().toISOString(),
};

export class DiariesModel extends AWSDynamo<Diaries> {
  constructor(data?: Partial<Diaries>) {
    super(TABLE.diaries);
    this.data = {
      ...defaultDiaries,
      ...data,
    };
  }
  data: Diaries;
}
