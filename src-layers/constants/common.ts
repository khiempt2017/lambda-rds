export const BP_SCALE_DEF_AUTO_STRETCH = -1;

export const GRAPH_MASK_DEFAULT = 1;

export const BP_MORNING_START = '04:00:00';

export const BP_MORNING_END = '11:59:00';

export const BP_EVENING_START = '19:00:00';

export const BP_EVENING_END = '01:59:00';

export const DEFAULT_SYS = 135;

export const DEFAULT_DIA = 85;

export const DEFAULT_MEMO_MASK = 76448;

export const ORDER_LENGTH = 17;

export const DATE_VALID_180 = 180;

export const DATE_VALID_31 = 31;

export const MAX_LENGTH_USER_ID_LIST = 100;

export const MOST_PAST_DATE = '2016-04-01';

export const DEVICE_CATEGORY_VITALS = '0';

export const DEVICE_CATEGORY_WEIGHT = '1';

export const DEVICE_CATEGORY_STEPS = '2';

export const API_KEY = btoa('OMRON BP DIARY 20180226');

export const DEVICE_IN_USE = '1';

export const REGEX_YYYYMMDDHHmm =
  '^(?:(?:19|20)\\d{2})(0[1-9]|1[0-2])(?:(0[1-9]|1\\d|2[0-9]|(29|30)(?!(?:02))|31(?!(?:02|04|06|09|11))))([01]\\d|2[0-3])([0-5]\\d)$';

export const REGEX_YYYY_MM_DD =
  '^(?:(?:19|20)\\d{2})-(0[1-9]|1[0-2])-(0[1-9]|1\\d|2[0-9]|(29|30)(?!(?:-02))|31(?!(?:-02|-04|-06|-09|-11))|02-29(?=(?:19|20)(?:[02468][048]|[13579][26])))$';

export const REGEX_YYYY_MM_DD_HH_mm =
  '^(?:(?:19|20)\\d{2})-(0[1-9]|1[0-2])-(0[1-9]|1\\d|2[0-9]|(29|30)(?!(?:-02))|31(?!(?:-02|-04|-06|-09|-11))|02-29(?=(?:19|20)(?:[02468][048]|[13579][26]))) ([01]\\d|2[0-3]):([0-5]\\d)$';

export const REGEX_HH_mm = '^(?:[01][0-9]|2[0-3]):[0-5][0-9]$';

export const REGEX_YYYYMMDD =
  '^(?:(?:19|20)\\d{2})(0[1-9]|1[0-2])(?:(0[1-9]|1\\d|2[0-9]|(29|30)(?!(?:02))|31(?!(?:02|04|06|09|11))))$';

export const LIMIT_USER = 2000;

export const TABLE_DIARIES = 'diaries';

export const TABLE_MAILS = 'mails';

export const TABLE_CONST_LIST = 'TABLE_CONST_LIST';

export const TABLE_LIST = ['banner_management', 'memo_orders', 'settings'];

export const BATCH_SIZE = 2000;

export const CONCURRENCY_LIMIT = 5;

export const REGEX_YYYYMM = '^(?:(?:19|20)\\d{2})(0[1-9]|1[0-2])$';

export const DYNAMODB_BATCH_WRITE_LIMIT = 25;

export const SET_WITHDRAWAL_USER_API_NAME = 'set_withdrawal_user';

export const PAY_PER_REQUEST = 'PAY_PER_REQUEST';

export const WITHDRAWAL_USER_CSV_FILE_PATH = '/tmp/withdrawalUserListFromOGSC.csv';

export const CSV_BATCH_SIZE = 2000;

export const CSV_FOLDER_NAME = 'diary_data';

export const CSV_STREAM_BUFFER_SIZE = 65536;

export const S3_MAX_RETRIES = 3;

export const S3_PART_SIZE = 5242880;

export const S3_STORAGE_CLASS = 'GLACIER_IR';

export const S3_ACL_PUBLIC_READ = 'public-read';

export const S3_DEFAULT_CONTENT_TYPE = 'application/octet-stream';

export const RETRYABLE_ERROR_CODES = [
  'RequestTimeout',
  'TimeoutError',
  'NetworkingError',
  'ProvisionedThroughputExceededException',
];

export const DYNAMODB_BACKUP_LIFE_CYCLE = 35;

export const DELETE_WITHDRAWAL_USER_JOB_DEFINITION = 'batch-delete_withdrawal_user-execution-job-definition';

export const DELETE_WITHDRAWAL_USER_JOB_QUEUE = 'batch-delete_withdrawal_user-execution-job-queue';

export const EXPORT_CSV_JOB_DEFINITION = 'batch-export_csv-execution-job-definition';

export const EXPORT_CSV_JOB_QUEUE = 'batch-export_csv-execution-job-queue';

export const BP_COUNT = 2;

// Email related constants
export const EMAIL_DAYS_BACK = 8;
export const EMAIL_REPORT_DAYS = 7;
export const EMAIL_DEFAULT_TOTAL_COUNT = 1;
export const EMAIL_STATUS_SENT = 2;

// Language related constants
export const LANGUAGE = {
  DEFAULT: 'JA',
  CHINESE: 'ZH',
  SCRIPT: {
    HANS: 'hans'
  },
  COUNTRY: {
    CHINA: 'CN'
  },
  SUPPORTED: ['ZH', 'JA']
} as const;

// Report measurement related constants
export const REPORT_MEASURE = {
  DAYS_IN_WEEK: 7,
  WEEK_DAY_ADJUST: 1, // For adjusting weekD - 1
  INITIAL_COUNT: 0,
  MIN_MEASURE_COUNT: 0
} as const;

// Date format constants
export const DATE_FORMAT = {
  TIMESTAMP: 'YYYYMMDDHHmm',
  YYYYMMDD: 'YYYY-MM-DD',
  MONTH_DAY: 'M/D',
  YYYYMMDD_HHmmss: 'YYYY-MM-DD HH:mm:ss'
} as const;

// Common numeric constants
export const NUMERIC = {
  ZERO: 0,
  ONE: 1,
  TWO: 2,
  SEVEN: 7,
  TEN: 10,
  FIFTEEN: 15,
  TWENTY: 20,
  TWENTY_FIVE: 25,
  SIXTY: 60,
  NINETEEN: 19,
} as const;

export const MEASURE = {
  SYS_THRESHOLD: {
    HIGH: 25,   // systolic - index_sys >= 25
    MEDIUM: 15  // systolic - index_sys >= 15
  },
  DIA_THRESHOLD: {
    HIGH: 20,   // diastolic - index_dia >= 20
    MEDIUM: 10  // diastolic - index_dia >= 10
  },
  TIME_WINDOW: {
    MINUTES: 10,
    SUBSTRING_LENGTH: 19
  }
} as const;

export const MEASURE_PARAMS = {
  TIME_UNIT: 'seconds',
  TYPE_NUMBER: 'number'
} as const;

export const AUTO_SEND_STATUS = {
  ENABLED: 1,
  DISABLED: 0
} as const;

export const IS_SELF_STATUS = {
  YES: 1,
  NO: 0
} as const;

export const APP_REGION = {
  EU: 'EU',
} as const;

export const TIME = {
  SECONDS_IN_10_MINS: 600, // 10 * 60 seconds
  STRING_LENGTH_19: 19,    // For substring(0, 19)
} as const;

export const ERROR_CODES = {
  NIGHT_ERROR_DEFAULT: 0,
  NIGHT_ERROR_SPECIAL: 99
} as const;

export const DAYS = {
  TWO: 2  // For adding 2 days
} as const;

export const BP = {
  COUNT: 2,            // For BP_COUNT
  NUM_DAYS: 7,        // For numDays
  DEFAULT_COUNT: 0,    // For default count values
  INITIAL_VALUES: {
    COUNT: 0,
    SYSTOLIC: 0,
    DIASTOLIC: 0,
    PULSE: 0,
    COUNT_SYSTOLIC: 0,
    COUNT_DIASTOLIC: 0,
    COUNT_PULSE: 0
  },
  MODE_EVENING: {
    HEX_VALUE: '02',
    MIN_VALUE: 0,
    LAST_CHARS_LENGTH: 2,  // For hexString.slice(-2)
    HEX_RADIX: 16  // For toString(16) conversion
  }
} as const;
export const GRANT_TYPE = {
  AUTHORIZATION_CODE: 'authorization_code',
};
export const EMAIL_STATUS = {
  UNPROCESSED: 0,
  IN_PROCESS: 1,
  SENT: 2,
  NOT_COVERED: 3
};
export const REGULAR_REPORT_USER_LIMIT = {
  MAX: 20,
  DEFAULT: 10,
};

export const PUBLIC_FOLDER = {
  IMAGES_CONFIRM_EMAIL_SUCCESS: '/opt/public/imgs/confirm_email_success/',
};

