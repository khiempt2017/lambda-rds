/**
 * BP-api-serverless
 * Created by khuongdv <khuongdv@vitalify.asia> on 2024/10/03
 * Copyright (c) 2024年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { AWSDynamo } from '../db/aws-dynamo';
import { TABLE } from '../../constants';

export type Greeting = {
  id: string;
  value: string;
};

export class GreetingModel extends AWSDynamo<Greeting> {
  constructor() {
    super(TABLE.greetings);
  }
}
