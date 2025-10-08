/**
 * aws-serverless
 * Created by LoiNV <loinv@vitalify.asia> on 2024/10/08
 * Copyright (c) 2024 VFA Asia Co.,Ltd. All rights reserved.
 */

export const WRONG_AUTHENTICATION_MESSAGE = 'Wrong Authorization Header.';

export const SERVER_ERROR_MESSAGE = 'Internal server error.';

export const INVALID_JSON_MESSAGE = 'Require a valid json format.';

export const INVALID_INPUT_MESSAGE = 'Invalid input.';

export const REQUESTING_FOR_USER_SET_MESSAGE_FLAG = 'Requesting for user setMessageFlag.';

export const REQUESTING_FOR_USER_GET_MESSAGE_FLAG = 'Requesting for user getMessageFlag.';

export const REQUESTING_FOR_USER_SETTINGS = 'Requesting for user settings.';

export const REQUESTING_FOR_USER_GET_VERIFY_LINK = 'Requesting for user getVerifyLink.';

export const REQUESTING_FOR_USER_VERIFY_EMAIL = 'Requesting for user verifyEmail.';

export const REQUESTING_FOR_USER_UNSUBSCRIBE = 'Requesting for user unsubscribe.';

export const REQUESTING_FOR_USER_DELETE_EMAIL = 'Requesting for user deleteEmail.';

export const UPDATE_MESSAGE_FLAG_SUCCESS = 'Update message_flag success!';

export const USER_ID_NOT_FOUND = 'user_id not found';

export const WRONG_TOKEN = 'WRONG_TOKEN';

export const INVALID_ARRAY_ELEMENT =
  'Error: The element in array should be number and greater than 0 and smaller or equal 17';

export const REQUESTING_FOR_USER_SET_MEMO = 'Requesting for user setMemo.';

export const REQUESTING_FOR_USER_REGISTER_SETTINGS = 'Requesting for user register settings.';

export const DATE_INVALID = 'Date validation failed';

export const DATE_PERIOD_MESSAGE = 'The period from start_date to end_date must be {numberDays} days or less.';

export const MAX_LENGTH_USER_ID = 'user_id_list must be less than {maxLength}';

export const REQUESTING_FOR_USER_GET_VITAL_DATA = 'Requesting for user getVitalData.';

export const REQUESTING_FOR_DEVICE_INFORMATION = 'Requesting for device information.';

export const INVALID_DATE_FORMAT_YYYYMMDDHHmm = 'Invalid date format. Expected format: YYYYMMDDHHmm.';

export const INVALID_DATE_FORMAT_YYYY_MM_DD = 'Invalid date format. Expected format: YYYY-MM-DD.';

export const INVALID_DATE_FORMAT_YYYY_MM_DD_HH_mm = 'Invalid date format. Expected format: YYYY-MM-DD HH:mm.';

export const INVALID_TIME_FORMAT_HH_mm = 'Invalid time format. Expected format: HH:mm.';

export const INVALID_DATE_FORMAT_YYYYMMDD = 'Invalid date format. Expected format: YYYYMMDD.';

export const INVALID_DATE_FORMAT_YYYYMM = 'Invalid date format. Expected format: YYYYMM.';

export const REQUESTING_FOR_GET_WITHDRAWAL_USER_LIST = 'Requesting for getting and saving withdrawal user list start.';

export const REQUESTING_FOR_DELETE_WITHDRAWAL_USER = 'Requesting for deleting withdrawal user.';

export const WRONG_AUTH_OGSC_ROLE_SYSTEM_ACCOUNT = 'The OGSC role system account is wrong authorization';

export const SET_WITHDRAWAL_USERS_SUCCESS = 'Set withdrawal users successfully.';

export const GET_FILE_FROM_OGSC_FAILURE = 'Get file from ogsc is failure';

export const MISSING_TOKEN_OGSC_URL = 'Missing TOKEN_OGSC_URL environment variable';

export const MISSING_OGSC_URL = 'Missing OGSC_URL environment variable';

export const MISSING_X_KII_APP_ID = 'Missing X_KII_APP_ID environment variable';

export const MISSING_X_KII_APP_KEY = 'Missing X_KII_APP_KEY environment variable';

export const MISSING_OGSC_ADMIN_USERNAME = 'Missing OGSC_ADMIN_USERNAME environment variable';

export const MISSING_OGSC_ADMIN_PASSWORD = 'Missing OGSC_ADMIN_PASSWORD environment variable';

export const MISSING_S3_BUCKET_NAME = 'Missing S3_BUCKET_NAME environment variable';

export const START_SAVING_WITHDRAWAL_USER_LIST = 'Start saving Withdrawal User list: {userIdList}';

export const FINISH_SAVING_WITHDRAWAL_USER_LIST = 'Finish saving Withdrawal User list';

export const REQUESTING_FOR_WRITE_LOG = 'Requesting for writelog.';

export const END_REQUESTING_FOR_WRITE_LOG = 'End process writelog.';

export const USER_START_REQUEST = 'Requesting for user start.';

export const USER_EXPORT_START = 'Requesting for user getAllUserSetting.';

export const EXPORT_START = 'Start export';

export const EXPORT_DONE = 'Done export';

export const FILES_UPLOAD = 'Upload files';

export const FILES_UPLOADED = 'Uploaded files';

export const GET_ALL_USER_SETTINGS = 'getAllUserSetting';

export const OPERATION_DONE = 'Done';

export const EXPORT_PROCESSING_FOR_TABLE = 'getAllUserSetting exporting for {table}';

export const TABLE_BATCH_INFO = '{table}-batch: {batchNumber}';

export const EXPORT_COMPLETED_FOR_TABLE = 'exported {table}';

export const USER_SETTING_CSV_EXCEPTION = 'getAllUserSetting buildFileCSV Exception';

export const ERROR_CLEANING_TEMP_FILES = 'Error cleaning up temp file {file}: {error}';

export const UNKNOWN_TABLE_ERROR = 'Unknown table: {table}';

export const MAX_RETRIES_EXCEEDED = 'Max retries exceeded';

export const S3_FILE_NOT_FOUND = 'File not found: {filePath}';

export const S3_UPLOAD_START = 'Starting upload of {fileName} to {folderName}';

export const S3_UPLOAD_PROGRESS = 'Upload progress for {fileName}: {progress}%';

export const S3_UPLOAD_SUCCESS = 'Successfully uploaded {fileName} to {folderName}';

export const S3_UPLOAD_ERROR = 'Error uploading file to S3: {message}';

export const S3_UPLOAD_ID_ERROR = 'Failed to retrieve upload ID';

export const S3_MULTIPART_UPLOAD_FAILED = 'Multipart upload failed';

export const S3_ABORT_MULTIPART_UPLOAD_ERROR = 'Failed to abort multipart upload: {message}';

export const DELETE_WITHDRAWAL_USERS_SUCCESS = 'Delete withdraw users success!';

export const WARM_UP_LAMBDA_SUCCESS = 'Warm up lambda success!';

export const DELETE_INTERNAL_WITHDRAWAL_USER_SUCCESS = 'Delete internal withdrawal users success!';

export const BACKUP_DYNAMODB_SUCCESS = 'Backup job started successfully';

export const REQUESTING_FOR_USER_SEND_EMAIL = 'Requesting for user sendEmail.';

export const MISSING_TOKEN_URL = 'Missing TOKEN_URL in environment variables';

// Email service related messages
export const EMAIL_MESSAGES = {
  NOT_ENOUGH_CONDITION: 'This user is not enough condition to receive mail.',
  NO_REFRESH_TOKEN: 'This user is not enough condition to receive mail.',
  NO_ACCESS_TOKEN: 'Can not get access token from refresh token',
  NO_TOKEN: 'Can not get refresh token',
  NO_VITALS: "Can not get user's vitals",
  SEND_FAILED: 'Failed to send email'
} as const;

export const REQUESTING_FOR_USER_LOGIN_REGULAR_REPORT = 'Requesting for user loginRegularReport.';

export const REGULAR_REPORT_NOT_FOUND = 'Not found data with user_id {userId}';

export const REGULAR_REPORT_USER_NOT_MATCH = 'UserNotMatch';

export const REQUESTING_FOR_USER_SET_REGULAR_REPORT = 'Requesting for user setRegularReports.';

export const REGULAR_REPORT_WRONG_USER_ID = 'Wrong user_id';

export const REQUESTING_FOR_USER_GET_REGULAR_REPORT_SETTING = 'Requesting for user getRegularReportSetting.';

export const START_CALL_GET_REFRESH_TOKEN = 'Start call getRefreshToken function';

export const END_CALL_GET_REFRESH_TOKEN = 'End call getRefreshToken function';

export const GET_REFRESH_TOKEN_RESPONSE = 'Response from getRefreshToken:';

export const INVALID_USER_ID_FORMAT = 'Invalid format. Expected format: ^([a-zA-Z0-9\-])+$';
export const INVALID_SELF_MAIL_FORMAT = 'The self mail must be a valid email address.';
export const INVALID_SEND_EMAILS_FORMAT = 'The send emails must be a valid JSON string.';

export const INVALID_COUNT_FORMAT = 'The count must be a number.';
export const MINIMUM_COUNT = 'The count must be at least 1.';

export const REGULAR_REPORT_MESSAGES = {
  CREATE_NOT_SUCCESS: 'Create not success!',
  ALREADY_SET_IS_SELF: 'This user already set is_self',
  UPDATE_SUCCESS: 'Update success!',
  UPDATE_FAILED: 'Update failed!',
  RECORD_NOT_FOUND: 'Not found record_id match with user_id. Update failed!'
} as const;

export const NOT_FOUND_USER_ID_OR_EMAIL = 'Not found user_id or email';

export const EMAIL_NOT_FOUND = 'Not found email';
export const DELETE_EMAIL_ERROR = 'Error occurred while deleting email';

export const WRONG_USER_ID = 'Wrong user_id';
