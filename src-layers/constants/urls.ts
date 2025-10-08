/**
 * aws-serverless
 * Created by LoiNV <loinv@vitalify.asia> on 2024/10/07
 * Copyright (c) 2024 VFA Asia Co.,Ltd. All rights reserved.
 */

export const JWKS_URL = 'https://cognito-idp.AWS_COGNITO_REGION.amazonaws.com/AWS_COGNITO_USER_POOL_ID/.well-known/jwks.json';

export const USER_INFO_URL = '/oauth2/users/me/openid-connect';

export const LOCAL_DB_ENDPOINT = 'http://dynamo:8000';

export const MEASURE_LATEST_URL = '/server-code/versions/current/getLatestMeasureData';

export const DEVICE_URL = '/server-code/versions/current/searchDeviceConfData';

export const WITHDRAWAL_URL = '/system/withdrawn-user-information';

export const TOKEN_URL = '/oauth2/token';
