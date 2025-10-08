/**
 * banner-management.model.ts
 * aws-serverless
 * Created by nguyennt <nguyennt@vitalify.asia> on 2024/10/16
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { AWSDynamo } from '../db/aws-dynamo';
import { TABLE } from '../../constants';

export type BannerManagement = {
  user_id: string;
  banner_category: number; // default 0
  banner_displayed_date: string | null;
  created_at: string | null;
  updated_at: string | null;
};

export const defaultBannerManagement: BannerManagement = {
  user_id: '',
  banner_category: 0,
  banner_displayed_date: null,
  created_at: null,
  updated_at: null,
};

export class BannerManagementModel extends AWSDynamo<BannerManagement> {
  constructor(data?: Partial<BannerManagement>) {
    super(TABLE.bannerManagement);
    this.data = {
      ...defaultBannerManagement,
      ...data,
    };
  }
  data: BannerManagement;
}
