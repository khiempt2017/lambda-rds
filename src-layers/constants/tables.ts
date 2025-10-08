/**
 * BP-api-serverless
 * Created by khuongdv <khuongdv@vitalify.asia> on 2024/10/03
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

const TABLE_PREFIX = 'BPDiary';
const STAGE = process.env.STAGE || 'local';

export const TABLE = {
  greetings: `${TABLE_PREFIX}-greetings_${STAGE}`,
  regularReports: `${TABLE_PREFIX}-regular_report_${STAGE}`,
  settings: `${TABLE_PREFIX}-settings_${STAGE}`,
  commonSettings: `${TABLE_PREFIX}-common_settings_${STAGE}`,
  bannerManagement: `${TABLE_PREFIX}-banner_management_${STAGE}`,
  memoOrders: `${TABLE_PREFIX}-memo_orders_${STAGE}`,
  diaries: `${TABLE_PREFIX}-diaries_${STAGE}`,
  withdrawalUser: `${TABLE_PREFIX}-withdrawal_user_${STAGE}`,
  mails: `${TABLE_PREFIX}-mails_${STAGE}`,
  mailsClone: `${TABLE_PREFIX}-mails_clone_${STAGE}`,

  regularReportClone: `${TABLE_PREFIX}-regular_report_clone_${STAGE}`,
};
