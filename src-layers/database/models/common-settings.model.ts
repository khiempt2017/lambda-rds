/**
 * common-settings.model.ts
 * aws-serverless
 * Created by nguyennt <nguyennt@vitalify.asia> on 2024/10/16
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
import { AWSDynamo } from '../db/aws-dynamo';
import { TABLE } from '../../constants';

export type CommonSettings = {
  common_key: string;
  set_value: string | null;
  remarks: string | null;
};

export const defaultCommonSettings: CommonSettings = {
  common_key: '',
  set_value: null,
  remarks: null,
};

export class CommonSettingsModel extends AWSDynamo<CommonSettings> {
  constructor(data?: Partial<CommonSettings>) {
    super(TABLE.commonSettings);
    this.data = {
      ...defaultCommonSettings,
      ...data,
    };
  }
  data: CommonSettings;
}
