<?php

return array(
    'API_KEY' => base64_encode('OMRON BP DIARY 20180226'),
    'mail_from'        => [
        'JP' => 'ohqit_support@ohq.omron.co.jp',
        'IN' => 'ohsid_feedback@ap.omron.com',
        'AU' => 'sales@omronhealthcare.com.au',
        'SG' => 'ohs_feedback@ap.omron.com',
        'TH' => 'ohsth_csupport@ap.omron.com',
        'PH' => 'ohsph_feedback@ap.omron.com',
        'VN' => 'omronhealthcarevn@ap.omron.com',
        'MY' => 'ohsmy_feedback@ap.omron.com',
        'INDO' => 'callcentre@ap.omron.com',
    ],
    'mail_reply' => 'ogsc_support@ssa.omron.co.jp',
    'mail_from2' => 'noreply@bp-manager.com',
    'mail_bcc'         => '',
    'mail_username'    => "",
    'mail_password'    => "",
    'mail_host'        => "",
    'mail_subject'     => "OMRON Graph Report",
    'mail_pre_file'    => [
        'EN' => '[BP-Logbook]',
        'JA' => '[血圧手帳]',
        'ZH' => '[血壓手帳]',
        'ZH-HANS' => '[血压手帐]',
        'KO' => '[혈압수첩]',
        'DE' => '[BD-Logbuch]',
        'FR' => '[Carnet de bord de TA]',
        'IT' => '[Registro PA]',
        'ES' => '[Registro TA]',
        'NL' => '[BD-Logboek]',
        // 改造BP_APP_DEV-1038 2019.05.30 TranHV ---->
        // 改造BP_APP_DEV-1076 2019.06.12 TranHV ---->
        'PL' => '[BP-Logbook]',
        // BP_APP_DEV-1076 2019.06.12 <----
        'SV' => '[BP-Logbook]',
        'TR' => '[BP-Logbook]',
        'PT' => '[Livro de registros]',
        // BP_APP_DEV-1038 2019.05.30 <----
        // BP_APP_DEV-2009 2022.07.04 TrungNT ---->
        'VI' => '[Nhật ký huyết áp]',
        // BP_APP_DEV-2009 2022.07.04 <----
    ],
    'mail_to_name'     => 'OGSCクラウドのお客様',

    'ga_code'          => 'UA-69193763-18',
    //'ga_code'          => 'UA-109243783-2', // Id test

    'redirect_uri'     => 'https://52.199.120.104/ogsc', // for test on web browser

    'USER_INFO_URL'    => '/oauth2/users/me/openid-connect',
    'MEASURE_DATE_URL' => '/server-code/versions/current/measureData',
    'MEASURE_LATEST'     => '/server-code/versions/current/getLatestMeasureData',
    'TOKEN_URL'        => '/oauth2/token',
    'LOGIN_URL'        => '/oauth2/login',
    'DEVICE_URL'       =>'/server-code/versions/current/searchDeviceConfData',
    // 改造BP_APP_DEV-3334 2023.12.11 TrungNT ---->
    'WITHDRAWAL_URL'   => '/system/withdrawn-user-information',
    // 改造BP_APP_DEV-3334 2023.12.11 <----
    // 改造BP_APP_DEV-1385 2020.12.10 MinhTQ ---->
    // Define language support and default language if not support for regular report
    'SUPPORT_LANG'    => ['ZH','JA'],
    'DEFAULT_LANG'    => 'JA',
    // BP_APP_DEV-1385 2020.12.10 <----

    // Define default SYS
    'DEFAULT_SYS' => 135,
    // Define default DIA
    'DEFAULT_DIA' => 85,

    // regex to check emoji
    'EMOJI_REGEX' => '([*#0-9](?>\\xEF\\xB8\\x8F)?\\xE2\\x83\\xA3|\\xC2[\\xA9\\xAE]|\\xE2..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?(?>\\xEF\\xB8\\x8F)?|\\xE3(?>\\x80[\\xB0\\xBD]|\\x8A[\\x97\\x99])(?>\\xEF\\xB8\\x8F)?|\\xF0\\x9F(?>[\\x80-\\x86].(?>\\xEF\\xB8\\x8F)?|\\x87.\\xF0\\x9F\\x87.|..(\\xF0\\x9F\\x8F[\\xBB-\\xBF])?|(((?<zwj>\\xE2\\x80\\x8D)\\xE2\\x9D\\xA4\\xEF\\xB8\\x8F\k<zwj>\\xF0\\x9F..(\k<zwj>\\xF0\\x9F\\x91.)?|(\\xE2\\x80\\x8D\\xF0\\x9F\\x91.){2,3}))?))',

    // bp count number
    'BP_COUNT' => 2,

    // MAIL TEMPLATE
    'JA' => [
        'title' => '[OMRON connect]血圧手帳送付のご案内',
        'body' =>
            "tpl_user 様\n\n" .
            "日頃より弊社OMRON connect（オムロンコネクト）をご利用いただきありがとうございます。\n" .
            "アプリから「らくらく血圧手帳」を作成された方にお送りしております。\n" .
            "設定された期間で血圧手帳を作成いたしました。添付ファイルをご確認くださいませ。\n" .
            "\n" .
            '添付ファイル："tpl_filename"' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "※本メールは送信専用アドレスから自動送信されています。返信を受信できないため、本メールに対するご返信はご遠慮下さい。\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => '血圧手帳（ 朝晩平均 ）',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' 週平均',
        'PDF_DATE_OUTPUT' => 'Y/m/d H:i',
        'PDF_DATE_FORMAT' => 'Y年m月d日',
        'PDF_MONTH_FORMAT' => 'n/j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'ja_JP',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'n/j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'Y年m月d日',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '〜',
        'PDF_NAME' => '名前：',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DATE' => '日 付',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATES' => ['月', '火', '水', '木', '金', '土', '日'],
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['１月', '２月', '３月', '４月', '５月', '６月', '７月','８月','９月','10月','11月','12月'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => '血　　圧',
        'PDF_TIME' => '測定時刻',
        'PDF_SYS' => '最高血圧',
        'PDF_DIA' => '最低血圧',
        'PDF_PULSE' => '脈　　拍',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => '診察',
        'PDF_MEDICATION' => '服薬回数',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_NOTES' => 'メモ（症状など）',
        'PDF_NOTES2' => '自由記入',
        'PDF_PERIOD' => '期 間',
        'PDF_AVG' => '測定日数',
        'PDF_AVG_PERIOD' => '期間全平均',
        'PDF_AVG_TEXT' => '平均',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DATE_CREATE' => '出力日時: ',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_FOOTER_LINE1' => '医師の方へ',
        'PDF_FOOTER_LINE2' => '※本グラフでは高血圧治療ガイドラインに準じ、起床時および就寝前の血圧値のみを表記しています。',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => '起床時（朝）の血圧/脈拍',
        'PDF_FOOTER_LINE4' => '就寝時（夜）の血圧/脈拍',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'mor_startからmor_endの時間帯で、最も早い時刻の測定記録から10分以内に測定した値のうち、最初から2回までの平均値です。',
        'PDF_FOOTER_LINE4_2' => 'eve_startからeve_endの時間帯で、最も遅い時刻の測定記録から10分以内に測定した値のうち、最後から2回までの平均値です。',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => '翌日',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_FOOTER_LINE5' => '起床時、就寝前ともに10分以内に2回もしくは1回しか測定しなかった場合は、2回の平均値または1回の測定値が表示されます。',
        'PDF_TITLE_CSV' => '測定日,タイムゾーン,最高血圧（mmHg）,最低血圧（mmHg）,脈拍（bpm）,機種',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_LESSTHAN_TEXT' => '未満',
        'PDF_YOUR_TARGET_SYS' => '目標最高血圧',
        'PDF_YOUR_TARGET_DIA' => '目標最低血圧',
        'PDF_ACHIEVEMENT_LEVEL' => '達成度',
        'PDF_TARGET_ACHIEVED' => '目標血圧値を達成',
        'PDF_EQUAL_OR_SLIGHTLY_1' => '目標血圧値と同じ',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'またはやや高い',
        'PDF_HIGHER' => '目標血圧値より高い',
        'PDF_MUCH_HIGHER' => '目標血圧値より非常に高い',
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => '血圧手帳（ 日中平均 ）',
        'PDF_DAY_AVG_FOOTER_1' => '日中平均',
        'PDF_DAY_AVG_FOOTER_2' => '1日の全ての測定値の平均',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => '血圧手帳（ 一覧 ）',
        'PDF_WEEK_AVG_NAME' => '名前',
        'PDF_WEEK_AVG_HEADER' => '週平均',
        'PDF_WEEK_PERIOD' => '期間',
        'PDF_WEEK_MOR_AVG' => '朝平均',
        'PDF_WEEK_EVE_AVG' => '晩平均',
        'PDF_WEEK_DAY_AVG' => '日中平均',
        'PDF_WEEK_SYS' => '最高血圧',
        'PDF_WEEK_DIA' => '最低血圧',
        'PDF_WEEK_PULSE' => '脈拍',
        'PDF_WEEK_ALL_RECORD_HEADER' => '全測定値',
        'PDF_WEEK_HINT_MODE_HEADER' => '測定モード、測定状態',
        'PDF_WEEK_HINT_HEADER' => '測定状態',
        'PDF_WEEK_HIND_NOTES' => 'メモアイコン',
        'PDF_WEEK_HINT_MODE' => 'モード',
        // 改造BP_APP_DEV-4288 2025.02.13 LoiNV ---->
        'PDF_WEEK_HINT_MODE_DES' => 'Afibモードで測定時に表示。',
        // 改造BP_APP_DEV-4288 2025.02.13 <----
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'モード',
        // 改造BP_APP_DEV-4288 2025.02.13 LoiNV ---->
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => '夜間モードで測定時に表示。',
        // 改造BP_APP_DEV-4288 2025.02.13 <----
        // BP_APP_DEV-1271 2020.04.21 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => '測定エラー',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => '測定時に測定姿勢ガイド、カフぴったり巻、体動のエラーが検知',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'された場合に表示。',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => '不規則脈波',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC' => '測定中の脈波の間隔が不規則な状態を2回以上検知すると表示。',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.14 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC' => '測定中に心房細動の可能性（AFib）を検出すると表示。',
        // 改造BP_APP_DEV-4599 2025.04.14 <----
        'PDF_WEEK_HINT_EXCERCISE' => '運動',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => '運動不足',
        'PDF_WEEK_HINT_REDUCING_SALT' => '塩分控え目',
        'PDF_WEEK_HINT_SALT' => '塩分',
        'PDF_WEEK_HINT_VEGETABLES' => '野菜多め',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => '野菜不足',
        'PDF_WEEK_HINT_NO_ALCOHOL' => '節酒',
        'PDF_WEEK_HINT_ALCOHOL' => 'お酒',
        'PDF_WEEK_HINT_SLEEP' => 'しっかり睡眠',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => '睡眠不足',
        'PDF_WEEK_HINT_NOT_SMOKING' => '禁煙',
        'PDF_WEEK_HINT_SMOKING' => '喫煙',
        'PDF_WEEK_HINT_CONSULTATION' => '病院',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => '測定状態',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => '脈間隔の乱れあり',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC' => '「脈間隔の乱れあり」は測定中の脈が常に不規則な時に表示。',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => '日付',
        'PDF_WEEK_TIME' => '測定時刻',
        'PDF_WEEK_DI' => '測定状態',
        'PDF_WEEK_MED' => '服薬',
        'PDF_WEEK_NOTES' => 'メモ（症状など）',
        'PDF_PAGE'  => 'Page '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'EN' => [
        'title' => '[OMRON connect]Sending Your BP-Logbook',
        'body' =>
            "Dear tpl_user,\n\n" .
            "Thank you for using our OMRON connect and Blood Pressure Management services.\n" .
            "Please find the exported Blood Pressure Logbook attached to this email.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            "Best regards,\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd. All Rights Reserved.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*This email message was sent from a notification-only address that cannot accept incoming email. Do not reply to this message.\n",

        // constants for pdf template send mail
        //'PDF_TITLE' => 'Morning & Night BP Logbook ( Period: ',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Blood Pressure Logbook ( Morning & Evening BP Avg. )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Average/wk.',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DATE_OUTPUT' => 'M j, Y H:i',
        // 改造BP_APP_DEV-1103 2019.09.17 TanND ---->
        'PDF_DATE_FORMAT' => 'j/n/Y',
        // BP_APP_DEV-1103 2019.09.17 <----
        'PDF_MONTH_FORMAT' => 'M j',
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'en_EN',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'M j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'M d D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => ' - ',
        'PDF_NAME' => 'Name: ',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DATE' => 'Date',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATES' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'BP',
        'PDF_TIME' => 'Time',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Consultation',
        'PDF_MEDICATION' => 'Medication',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_NOTES' => 'Notes',
        'PDF_NOTES2' => 'Notes',
        'PDF_PERIOD' => 'Period',
        'PDF_AVG' => 'Days measured/wk.',
        'PDF_AVG_PERIOD' => 'Average/period',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        // 改造BP_APP_DEV-1103 2019.09.17 TanND ---->
        'PDF_AVG_TEXT' => 'Avg',
        // BP_APP_DEV-1103 2019.09.17 <----
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_CREATE' => 'Create PDF on: ',
        'PDF_FOOTER_LINE1' => 'To medical professionals',
        'PDF_FOOTER_LINE2' => '* This chart is plotted only with blood pressure values around waking time and bedtime according to hypertensive treatment guidelines.',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_FOOTER_LINE3' => 'Morning Blood Pressure & Pulse',
        'PDF_FOOTER_LINE4' => 'Evening Blood Pressure & Pulse',
        // 改造BP_APP_DEV-1140 2019.09.25 VinhHP ---->
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'The average of the first 2 readings taken within 10 minutes between mor_start and mor_end.',
        'PDF_FOOTER_LINE4_2' => 'The average of the last 2 readings taken within 10 minutes between eve_start and eve_end.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        // BP_APP_DEV-1140 2019.09.25 <----
        'PDF_NEXT_DAY'      => 'next day',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_FOOTER_LINE5' => 'If the number of measurements is less than three, the average will be calculated based on the number of times measured.',
        'PDF_TITLE_CSV' => 'Measurement Date,Time Zone,SYS,DIA,Pulse,Device Model Name',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_LESSTHAN_TEXT' => 'Less than',
        'PDF_YOUR_TARGET_SYS' => 'Your target SYS',
        'PDF_YOUR_TARGET_DIA' => 'Your target DIA',
        'PDF_ACHIEVEMENT_LEVEL' => 'Achievement Level',
        'PDF_TARGET_ACHIEVED' => 'Target Achieved',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Equal or Slightly',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'Higher',
        'PDF_HIGHER' => 'Higher',
        'PDF_MUCH_HIGHER' => 'Much Higher',
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Blood Pressure Logbook (Daily Avg.)',
        'PDF_DAY_AVG_FOOTER_1' => 'Daily Average',
        'PDF_DAY_AVG_FOOTER_2' => 'The average of all data measurement in a day.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Blood Pressure Logbook (List)',
        'PDF_WEEK_AVG_NAME' => 'Name',
        'PDF_WEEK_AVG_HEADER' => 'Weekly Average',
        'PDF_WEEK_PERIOD' => 'Period',
        'PDF_WEEK_MOR_AVG' => 'Morning Avg.',
        'PDF_WEEK_EVE_AVG' => 'Evening Avg.',
        'PDF_WEEK_DAY_AVG' => 'Daily Avg.',
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'All Readings',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Mode, Detected Info (D.I.)',
        'PDF_WEEK_HINT_HEADER' => 'Detected Info (D.I.)',
        // 改造BP_APP_DEV-1103 2019.09.17 TanND ---->
        'PDF_WEEK_HIND_NOTES' => 'Memo Icon',
        // BP_APP_DEV-1103 2019.09.17 <----
        'PDF_WEEK_HINT_MODE' => 'Mode',
        'PDF_WEEK_HINT_MODE_DES' => 'Appears when measuring in Afib mode.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Mode',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Appears when measuring in nocturnal mode.',
        // BP_APP_DEV-1271 2020.04.21 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Measurement Error',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Appears when an error in Positioning Indicator,  Cuff Wrap Guide, or',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'Body Movement is detected during the measurement.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Irregular heartbeat',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'An irregular rhythm was detected two or more times during a',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'measurement.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Appears when Possible AFib (Atrial Fibrillation) is detected during',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'measurement.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        // 改造BP_APP_DEV-1103 2019.09.18 VinhHP ---->
        'PDF_WEEK_HINT_EXCERCISE' => 'Exercise',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Lack of Exercise',
        // BP_APP_DEV-1103 2019.09.18 <----
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Reducing Salt',
        'PDF_WEEK_HINT_SALT' => 'Salt',
        'PDF_WEEK_HINT_VEGETABLES' => 'Vegetables',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Less vegetables',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'No alcohol',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alcohol',
        'PDF_WEEK_HINT_SLEEP' => 'Sleep',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Lack of sleep',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Not smoking',
        'PDF_WEEK_HINT_SMOKING' => 'Smoking',
        'PDF_WEEK_HINT_CONSULTATION' => 'Consultation',
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Detected Info (D.I.)',
        // BP_APP_DEV-1059 2019.09.17 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Date',
        'PDF_WEEK_TIME' => 'Time',
        'PDF_WEEK_DI' => 'D.I.',
        'PDF_WEEK_MED' => 'Med.',
        'PDF_WEEK_NOTES' => 'Notes',
        'PDF_PAGE'  => 'Page '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'ZH' => [
        'title' => '[OMRON connect]如何寄送您的血壓手帳',
        'body' =>
            "親愛的 tpl_user\n\n" .
            "感謝您使用OMRON connect 以及血壓管理服務。\n" .
            "信件內容為「血壓手帳」的資料. 檔案已根據您選擇的期間匯出完畢，請見信件裡的附檔。\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*本信件為自動發送.請勿回信至本信件。\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_TITLE' => '血壓手帳（早晚平均）',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TITLE_SUB' => ' 週平均',
        'PDF_DATE_OUTPUT' => 'Y/m/d H:i',
        'PDF_DATE_FORMAT' => 'Y年m月d日',
        'PDF_MONTH_FORMAT' => 'n/j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'zh_CN',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'n/j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'Y年m月d日',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '〜',
        'PDF_NAME' => '姓名：',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => '日期',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_DATES' => ['一', '二', '三', '四', '五', '六', '日'],
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['一月', '二月', '三月', '四月', '五月', '六月', '七月','八月','九月','十月','十一月','十二月'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => '血　　壓',
        'PDF_TIME' => '測量時間',
        'PDF_SYS' => '收縮壓',
        'PDF_DIA' => '舒張壓',
        'PDF_PULSE' => '脈　　搏',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => '診斷',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => '服藥次數',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => '備註(症狀等)',
        'PDF_NOTES2' => '自由記錄',
        'PDF_PERIOD' => '期 間',
        'PDF_AVG' => '一週內測量天數',
        'PDF_AVG_PERIOD' => '期間內平均',
        'PDF_AVG_TEXT' => '平均',
        'PDF_DATE_CREATE' => '輸出日期: ',
        'PDF_FOOTER_LINE1' => '給醫師',
        'PDF_FOOTER_LINE2' => '※本圖表根據高血壓治療指南，只會顯示起床跟睡前的血壓值。',

        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => '起床（早晨）的血壓/脈搏',
        'PDF_FOOTER_LINE4' => '睡前（夜晚）的血壓/脈搏',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => '起床時、睡前10分鐘內若只有測量2次或1次時，只會顯示2次的平均值或測量1次的數值。',
        'PDF_TITLE_CSV' => '測量日期,時區,收縮壓(mmHg),舒張壓(mmHg),脈搏(bpm),機種',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_LESSTHAN_TEXT' => '小於',
        'PDF_YOUR_TARGET_SYS' => '目標收縮壓',
        'PDF_YOUR_TARGET_DIA' => '目標舒張壓',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => '成果等級',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => '達到目標',
        'PDF_EQUAL_OR_SLIGHTLY_1' => '等於或略高',
        'PDF_EQUAL_OR_SLIGHTLY_2' => '',
        'PDF_HIGHER' => '較高',
        'PDF_MUCH_HIGHER' => '高出很多',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'mor_start到mor_end這段時間內，最早時間的10分鐘內2次測量的血壓/脈搏平均值。',
        'PDF_FOOTER_LINE4_2' => 'eve_start到eve_end這段時間內，最晚時間的10分鐘內2次測量的血壓/脈搏平均值。',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DAY_AVG_TITLE' => '血壓手帳（一日平均）',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_DAY_AVG_FOOTER_1' => '一日平均',
        'PDF_DAY_AVG_FOOTER_2' => '一天中全部測量值的平均',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => '血壓記錄表',
        'PDF_WEEK_AVG_NAME' => '姓名: ',
        'PDF_WEEK_AVG_HEADER' => '一週平均',
        'PDF_WEEK_PERIOD' => '期間內平均',
        'PDF_WEEK_MOR_AVG' => '晨報平均',
        'PDF_WEEK_EVE_AVG' => '平均晚報',
        'PDF_WEEK_DAY_AVG' => '一日平均',
        'PDF_WEEK_SYS' => '收縮壓',
        'PDF_WEEK_DIA' => '舒張壓',
        'PDF_WEEK_PULSE' => '脈搏',
        'PDF_WEEK_ALL_RECORD_HEADER' => '全部測量值',
        'PDF_WEEK_HINT_MODE_HEADER' => '模式, 測量狀態',
        'PDF_WEEK_HINT_HEADER' => '測量狀態',
        'PDF_WEEK_HIND_NOTES' => '狀況備註',
        'PDF_WEEK_HINT_MODE' => '模式',
        'PDF_WEEK_HINT_MODE_DES' => '在 Afib 模式中測量時就會顯示',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => '模式',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => '在 夜眠期模式中測量時就會顯示',
        // BP_APP_DEV-1271 2020.04.21 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => '測量錯誤',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => '測量過程中若偵測到「測量高度指引」、「壓脈帶著裝確認」或',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => '「身體晃動檢測」有誤，就會顯示。',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => '不規則脈波',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC' => '測量過程中偵測到二或多次不規則脈波時就會顯示。',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.14 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC' => '測量中檢測到可能的房顫 (AFib) 時顯示。',
        // 改造BP_APP_DEV-4599 2025.04.14 <----
        'PDF_WEEK_HINT_EXCERCISE' => '運動',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => '運動不足',
        'PDF_WEEK_HINT_REDUCING_SALT' => '少鹽日',
        'PDF_WEEK_HINT_SALT' => '攝取鹽分',
        'PDF_WEEK_HINT_VEGETABLES' => '多蔬菜',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => '少蔬菜',
        'PDF_WEEK_HINT_NO_ALCOHOL' => '無喝酒',
        'PDF_WEEK_HINT_ALCOHOL' => '喝酒',
        'PDF_WEEK_HINT_SLEEP' => '睡眠充足',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => '睡眠不足',
        'PDF_WEEK_HINT_NOT_SMOKING' => '禁煙',
        'PDF_WEEK_HINT_SMOKING' => '抽煙',
        'PDF_WEEK_HINT_CONSULTATION' => '診斷',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => '測量狀態',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => '日期',
        'PDF_WEEK_TIME' => '測量時間',
        'PDF_WEEK_DI' => '測量狀態',
        'PDF_WEEK_MED' => '服藥',
        'PDF_WEEK_NOTES' => '備註(症狀等)',
        'PDF_PAGE'  => '頁 '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    // 改造BP_APP_DEV-1380 2020.09.9 MinhTQ ---->
    // Add text for simplified chinese
    'ZH-HANS' => [
        'title' => '[欧姆龙笔记]血压手账发送向导',
        'body' =>
            "tpl_user 先生/女士,\n\n" .
            "感谢您一直以来使用本公司的OMRON connect。\n" .
            "我们通过应用向创建了“血压手账”的用户发送手账信息。\n" .
            "已根据您设置的期间创建了血压手账。请确认附件。\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd. All Rights Reserved.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "※本电子邮件通过发件专用地址自动发送。我们无法接收回信，因此请勿回复本电子邮件。\n" .
            "※如果您对本内容没有印象，请直接将本邮件的正文转发通知到“tpl_mail”。\n",
        'PDF_TITLE' => '血压手帐 (早晚平均)',
        'PDF_TITLE_SUB' => ' 周平均',
        'PDF_DATE_OUTPUT' => 'Y/m/d H:i',
        'PDF_DATE_FORMAT' => 'Y年m月d日',
        'PDF_MONTH_FORMAT' => 'n/j',
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'zh_ZH-CN',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        'PDF_WEEK_FORMAT' => 'n/j',
        'PDF_DAY_FORMAT' => 'Y年m月d日',
        'PDF_DATE_SEPARATE' => '〜',
        'PDF_NAME' => '姓名: ',
        'PDF_DATE' => '日期',
        'PDF_DATES' => ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
        'PDF_MONTH' => ['1月', '2月', '3月', '4月', '5月', '6月', '7月','8月','9月','10月','11月','12月'],
        'PDF_BP' => '血压',
        'PDF_TIME' => '测量时间',
        'PDF_SYS' => '高压',
        'PDF_DIA' => '低压',
        'PDF_PULSE' => '脉搏',
        'PDF_CONSULTATION' => '诊察',
        'PDF_MEDICATION' => '服药次数',
        'PDF_NOTES' => '备注（症状等）',
        'PDF_NOTES2' => '自由记录',
        'PDF_PERIOD' => '对象期间',
        'PDF_AVG' => '测量天数',
        'PDF_AVG_PERIOD' => '期间总平均',
        'PDF_AVG_TEXT' => '平均',
        'PDF_DATE_CREATE' => '输出日期及时间: ',
        'PDF_FOOTER_LINE1' => '致医生',
        'PDF_FOOTER_LINE2' => '※本图表根据高血压治疗指南，只显示起床时及睡前的血压值。',
        'PDF_FOOTER_LINE3' => '起床时（早晨）的血压/脉搏',
        'PDF_FOOTER_LINE4' => '睡前（夜间）的血压/脉搏',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'mor_start～mor_end这段时间内，自最早时间的测量记录起10分钟内2次测量的平均值。',
        'PDF_FOOTER_LINE4_2' => 'eve_start～次日eve_end这段时间内，自最晚时间的测量记录起10分钟内2次测量的平均值。',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => '',
        'PDF_FOOTER_LINE5' => '起床时、睡前的10分钟内均只测量了2次或1次时，则显示2次的平均值或1次的测量值。',
        'PDF_TITLE_CSV' => '测量日期,时区,高压（mmHg）,低压（mmHg）,脉搏（bpm）,机型',
        'PDF_LESSTHAN_TEXT' => '小于',
        'PDF_YOUR_TARGET_SYS' => '目标高压',
        'PDF_YOUR_TARGET_DIA' => '目标低压',
        'PDF_ACHIEVEMENT_LEVEL' => '达成度',
        'PDF_TARGET_ACHIEVED' => '达成目标',
        'PDF_EQUAL_OR_SLIGHTLY_1' => '等于或略高于',
        'PDF_EQUAL_OR_SLIGHTLY_2' => '目标血压值',
        'PDF_HIGHER' => '比目标血压值高',
        'PDF_MUCH_HIGHER' => '比目标血压值高很多',
        'PDF_DAY_AVG_TITLE' => '血压手帐（每日平均）',
        'PDF_DAY_AVG_FOOTER_1' => '每日平均',
        'PDF_DAY_AVG_FOOTER_2' => '一天中所有数据的平均值。',
        'PDF_WEEK_AVG_TITLE' => '血压记录表',
        'PDF_WEEK_AVG_NAME' => '姓名',
        'PDF_WEEK_AVG_HEADER' => '周平均',
        'PDF_WEEK_PERIOD' => '对象期间',
        'PDF_WEEK_MOR_AVG' => '晨报平均',
        'PDF_WEEK_EVE_AVG' => '平均晚报',
        'PDF_WEEK_DAY_AVG' => '每日平均',
        'PDF_WEEK_SYS' => '高压',
        'PDF_WEEK_DIA' => '低压',
        'PDF_WEEK_PULSE' => '脉搏',
        'PDF_WEEK_ALL_RECORD_HEADER' => '所有测量值',
        'PDF_WEEK_HINT_MODE_HEADER' => '模式, 测量状态',
        'PDF_WEEK_HINT_HEADER' => '测量状态',
        'PDF_WEEK_HIND_NOTES' => '状态标签图标',
        'PDF_WEEK_HINT_MODE' => '模式',
        'PDF_WEEK_HINT_MODE_DES' => '在Afib 模式测量时显示。',
        'PDF_WEEK_HINT_MODE_NIGHT' => '模式',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => '在夜晚模式中测量时显示',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => '测量错误',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => '在测量过程中检测到手腕位置、袖带佩戴自检或误动作提示错误时',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => '显示。',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => '不规则脉波',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC' => '在测量过程中两次或多次检测到脉波间隔不规则时显示。',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC' => '测量中检测到可能的房颤 (AFib) 时显示。',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => '运动',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => '运动不足',
        'PDF_WEEK_HINT_REDUCING_SALT' => '控盐',
        'PDF_WEEK_HINT_SALT' => '盐分',
        'PDF_WEEK_HINT_VEGETABLES' => '蔬菜丰富',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => '蔬菜不足',
        'PDF_WEEK_HINT_NO_ALCOHOL' => '节酒',
        'PDF_WEEK_HINT_ALCOHOL' => '饮酒',
        'PDF_WEEK_HINT_SLEEP' => '睡眠充足',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => '睡眠不足',
        'PDF_WEEK_HINT_NOT_SMOKING' => '禁烟',
        'PDF_WEEK_HINT_SMOKING' => '吸烟',
        'PDF_WEEK_HINT_CONSULTATION' => '诊察',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => '测量状态',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => '日期',
        'PDF_WEEK_TIME' => '测量时间',
        'PDF_WEEK_DI' => '测量状态',
        'PDF_WEEK_MED' => '服药',
        'PDF_WEEK_NOTES' => '备注（症状等）',
        'PDF_PAGE'  => '页 '
    ],
    // BP_APP_DEV-1380 2020.09.9 <----
    'KO' => [
        'title' => '[OMRON connect] 혈압수첩 전송에 관한 안내',
        'body' =>
            "tpl_user 님\n\n" .
            "항상 저희 OMRON connect 를 이용해 주셔서 감사합니다.\n" .
            "어플리케이션에서 '편리한 혈압 수첩'을 작성하신 분께 송신하고 있습니다.\n" .
            "설정된 기간에 혈압 수첩을 작성하셨습니다. 첨부 파일을 확인해 주십시오.\n" .
            "\n" .
            '첨부 파일: tpl_filename' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "※본 메일은 송신 전용 주소로부터 자동 송신되고 있습니다. 회신하실 경우에도 수신할 수 없으므로, 본 메일에 대한 회신을 삼가 주십시오.\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_TITLE' => '혈압수첩 아침・저녁 평균',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TITLE_SUB' => ' 주간 평군',
        'PDF_DATE_OUTPUT' => 'Y/m/d H:i',
        'PDF_DATE_FORMAT' => 'Y년m월d일',
        'PDF_MONTH_FORMAT' => 'n/j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'ko_KR',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'n/j',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'Y년m월d일',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => '성명: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => '일자',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_DATES' => ['월', '화', '수', '목', '금', '토', '일'],
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['1 월', '2 월', '3 월', '4 월', '5 월', '6 월', '7 월','8 월','9 월','10 월','11 월','12 월'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => '혈   압',
        'PDF_TIME' => '측정시간',
        'PDF_SYS' => '최고혈압',
        'PDF_DIA' => '최저혈압',
        'PDF_PULSE' => '맥   박',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => '병원 진찰',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => '복약 횟수',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => '메모(증상 등)',
        'PDF_NOTES2' => '비고(*자유 기입)',
        'PDF_PERIOD' => '기간',
        'PDF_AVG' => '측정 횟수',
        'PDF_AVG_PERIOD' => '측정 기간의 평균',
        'PDF_AVG_TEXT' => '평균',
        'PDF_DATE_CREATE' => '인쇄 일자: ',
        'PDF_FOOTER_LINE1' => '주치의 분들에게',
        'PDF_FOOTER_LINE2' => '※본 그래프는 가정혈압의 고혈압 기준에 준하여, 기상 및 취침전의 혈압 측정값을 표기하고 있습니다.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => '아침 혈압 및 맥박',
        'PDF_FOOTER_LINE4' => '저녁 혈압 및 맥박',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => '기상(아침) 및 취침(저녁)의 경우 10분이내에 2회 측정한 경우에는 2회 측정평균값이 표기되며, 1회 측정한 경우에는 1회 평균 측정값이 표기됩니다.',
        'PDF_TITLE_CSV' => '측정일시,시간 영역,최고혈압 (mmHg),최저혈압 (mmHg),맥박 (bpm),제품 기종',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_LESSTHAN_TEXT' => '미만',
        'PDF_YOUR_TARGET_SYS' => '목표 최고 혈압',
        'PDF_YOUR_TARGET_DIA' => '목표 최저 혈압',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => '달성도',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => '목표 혈압값 달성',
        'PDF_EQUAL_OR_SLIGHTLY_1' => '목표 혈압값과 ',
        'PDF_EQUAL_OR_SLIGHTLY_2' => '같거나 약간 높음',
        'PDF_HIGHER' => '목표 혈압값보다 높음',
        'PDF_MUCH_HIGHER' => '목표 혈압값보다 매우 높음',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'mor_start 부터 mor_end까지의 시간대에서 가정 먼저 측정한 측정값을 기준으로 10분 이내에 측정한 2회 평균값입니다.',
        'PDF_FOOTER_LINE4_2' => 'eve_start 부터 eve_end까지의 시간대에서 가정 먼저 측정한 측정값을 기준으로 10분 이내에 측정한 2회 평균값입니다.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => '다음날',
        //Need to update for this language
        // TEXT DAY AVERAGE
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DAY_AVG_TITLE' => '혈압수첩 낮 동안의 평균',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_DAY_AVG_FOOTER_1' => '낮 동안의 평균',
        'PDF_DAY_AVG_FOOTER_2' => '모든 측정값의 1일 평균',
        //WEEK AVG
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_WEEK_AVG_TITLE' => '혈압수첩 ( 혈압 기록표 )',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_AVG_NAME' => '성명: ',
        'PDF_WEEK_AVG_HEADER' => '주간 평균',
        'PDF_WEEK_PERIOD' => '기간',
        'PDF_WEEK_MOR_AVG' => '아침 평균',
        'PDF_WEEK_EVE_AVG' => '저녁 평균',
        'PDF_WEEK_DAY_AVG' => '낮 동안의 평균',
        'PDF_WEEK_SYS' => '최고혈압',
        'PDF_WEEK_DIA' => '최저혈압',
        'PDF_WEEK_PULSE' => '맥박',
        'PDF_WEEK_ALL_RECORD_HEADER' => '모든 측정값',
        'PDF_WEEK_HINT_MODE_HEADER' => '모드, 측정 상태',
        'PDF_WEEK_HINT_HEADER' => '측정 상태',
        'PDF_WEEK_HIND_NOTES' => '메모 아이콘',
        'PDF_WEEK_HINT_MODE' => '모드',
        'PDF_WEEK_HINT_MODE_DES' => 'Afib 모드 측정 시에 표시됩니다.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => '모드',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => '야간 모드 측정 시에 표시',
        // BP_APP_DEV-1271 2020.04.21 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => '측정 에러',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => '측정 도중 측정 자세 지시기, 커프 감기 가이드 또는 신체 활동에',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => '오류가 감지되는 경우 나타납니다.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => '북규칙맥파',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => '측정 중에 맥파의 간격이 불규칙한 상태를 2회 이상 감지할 경우에',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => '표시됩니다.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.14 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC' => '측정 중 심방세동(AFib) 가능성이 감지되면 표시됩니다.',
        // 改造BP_APP_DEV-4599 2025.04.14 <----
        'PDF_WEEK_HINT_EXCERCISE' => '운동',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => '운동부족',
        'PDF_WEEK_HINT_REDUCING_SALT' => '염분을 적게 섭취',
        'PDF_WEEK_HINT_SALT' => '염분',
        'PDF_WEEK_HINT_VEGETABLES' => '채소를 많이 섭취',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => '채소 부족',
        'PDF_WEEK_HINT_NO_ALCOHOL' => '절주',
        'PDF_WEEK_HINT_ALCOHOL' => '술',
        'PDF_WEEK_HINT_SLEEP' => '충분한 수면',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => '수면 부족',
        'PDF_WEEK_HINT_NOT_SMOKING' => '금연',
        'PDF_WEEK_HINT_SMOKING' => '흡연',
        'PDF_WEEK_HINT_CONSULTATION' => '병원 진찰',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => '측정 상태',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => '일자',
        'PDF_WEEK_TIME' => '측정시간',
        'PDF_WEEK_DI' => '측정 상태',
        'PDF_WEEK_MED' => '복약',
        'PDF_WEEK_NOTES' => '메모(증상 등)',
        'PDF_PAGE'  => '페이지 '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'DE' => [
        'title' => '[OMRON connect]Ihr Blutdruck-Logbuch wird gesendet',
        'body' =>
            "Sehr geehrter tpl_user,\n\n" .
            "Vielen Dank, dass Sie unser OMRON connect und die Blutdruck-Management-Services verwenden.\n" .
            "Als Anlage zu dieser E-Mail finden Sie das exportierte Blutdruck-Logbuch.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Mit freundlichen Grüßen,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  Alle Rechte vorbehalten.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Diese E‑Mail-Nachricht wurde Ihnen von einer reinen Benachrichtigungsadresse geschickt, die keine eingehenden E‑Mails akzeptiert. Bitte nicht auf diese Nachricht antworten.\n" .
            '*Falls Sie unseren Service nicht in Anspruch genommen haben, oder wenn der Benutzername nicht der Ihre ist, leiten Sie diese E-Mail bitte an „tpl_mail“ weiter.' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Blutdruck-Logbuch (Durchschn. BD am Morgen und am Abend )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Durchschn./Wo.',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'm.d.Y',
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'de_DE',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'd/m/Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Name: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Datum',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['Mo.', 'Di.', 'Mi.', ' Do.', 'Fr.', 'Sa.', 'So.'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Juni', 'Juli','Aug','Sep','Okt','Nov','Dez'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Blutdruck',
        'PDF_TIME' => 'Zeit',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Arztbesuch',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'Medikation',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Notizen',
        'PDF_NOTES2' => 'Notizen',
        'PDF_PERIOD' => 'Zeitraum',
        'PDF_AVG' => 'Messtage/Wo.',
        'PDF_AVG_PERIOD' => 'Durchschn./Periode',
        'PDF_AVG_TEXT' => 'MW',
        'PDF_DATE_CREATE' => 'PDF erstellt am: ',
        'PDF_FOOTER_LINE1' => 'Für medizinische Fachpersonen',
        'PDF_FOOTER_LINE2' => '* Diese Tabelle wird nur mit Blutdruckwerten zur Aufwach- und Zubettgehzeit entsprechend den Richtlinien zur Hypertoniebehandlung dargestellt.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Morgenblutdruck und Puls',
        'PDF_FOOTER_LINE4' => 'Abendblutdruck und Puls',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Sofern weniger als drei Messdaten vorliegen, wird der Durchschnitt auf Grundlage der vorliegenden Daten für den entsprechenden Zeitraum berechnet.',
        'PDF_TITLE_CSV' => 'Messdatum,Zeitzone,SYS,DIA,Pulse,Modellname des Geräts',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'Ihr SYS-Zielwert',
        'PDF_YOUR_TARGET_DIA' => 'Ihr DIA-Zielwert',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Erfolgsstufe',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Erreichtes Ziel',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Gleich oder etwas',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'höher',
        'PDF_HIGHER' => 'Höher',
        'PDF_MUCH_HIGHER' => 'Viel höher',
        'PDF_FOOTER_LINE3_2' => 'Der Durchschnitt der ersten 3 Messwerte, die innerhalb von 10 Minuten zwischen mor_start Uhr und mor_end Uhr gemessen wurden.',
        'PDF_FOOTER_LINE4_2' => 'Der Durchschnitt der letzten 3 Messwerte, die innerhalb von 10 Minuten zwischen eve_start Uhr und eve_end Uhr gemessen wurden.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Blutdruck-Logbuch (Tagesdurchschn. )',
        'PDF_DAY_AVG_FOOTER_1' => 'Tagesdurchschnitt',
        'PDF_DAY_AVG_FOOTER_2' => 'Der Durchschnitt aller an einem Tag gemessenen Daten.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Blutdruck-Logbuch ( Liste )',
        'PDF_WEEK_AVG_NAME' => 'Name',
        'PDF_WEEK_AVG_HEADER' => 'Wochendurchschnitt',
        'PDF_WEEK_PERIOD' => 'Zeitraum',
        'PDF_WEEK_MOR_AVG' => 'Morgen Durchschnitt',
        'PDF_WEEK_EVE_AVG' => 'Abend Durchschnitt',
        'PDF_WEEK_DAY_AVG' => 'Tagesdurchschn.',
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Alle Messwerte',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Modus, Ermittelte Daten (E.D.)',
        'PDF_WEEK_HINT_HEADER' => 'Ermittelte Daten (E.D.)',
        'PDF_WEEK_HIND_NOTES' => 'Memo-Symbol',
        'PDF_WEEK_HINT_MODE' => 'Modus',
        'PDF_WEEK_HINT_MODE_DES' => 'Wird angezeigt bei Messungen im AFib-Modus.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Modus',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Wird angezeigt bei Messungen im Nachtzeitmodus.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // 改造BP_APP_DEV-1103 2019.09.18 VinhHP ---->
        'PDF_WEEK_HINT_MODE_DES_1' => 'Wird angezeigt bei Messungen im AFib-Modus.',
        'PDF_WEEK_HINT_MODE_DES_2' => '',
        // BP_APP_DEV-1103 2019.09.18 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Messfehler',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Wird angezeigt, wenn ein Fehler in der Positionierungsanzeige,',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'in der Manschettensitzkontrolle oder Körperbewegungen während',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'der Messung festgestellt werden.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Unregelmäßiger Herzschlag',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Während einer Messung wurde zweimal oder öfter ein',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'unregelmäßiger Rhythmus erkannt.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'AFib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Wird angezeigt, wenn während der Messung mögliches',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'Vorhofflimmern  (AFib) erkannt wird.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Sportliche Aktivität',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Keine Aktivität',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Salz reduziert',
        'PDF_WEEK_HINT_SALT' => 'Salz',
        'PDF_WEEK_HINT_VEGETABLES' => 'Gemüse',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Weniger Gemüse',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Kein Alkohol',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alkohol',
        'PDF_WEEK_HINT_SLEEP' => 'Schlaf',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Zu wenig Schlaf',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Nicht geraucht',
        'PDF_WEEK_HINT_SMOKING' => 'Geraucht',
        'PDF_WEEK_HINT_CONSULTATION' => 'Arztbesuch',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Ermittelte Daten (E.D.)',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Datum',
        'PDF_WEEK_TIME' => 'Zeit',
        'PDF_WEEK_DI' => 'E.D.',
        'PDF_WEEK_MED' => 'Med.',
        'PDF_WEEK_NOTES' => 'Notizen',
        'PDF_PAGE'  => 'Seite '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'FR' => [
        'title' => '[OMRON connect]Envoi de votre carnet de bord de tension artérielle en cours',
        'body' =>
            "Cher/chère tpl_user,\n\n" .
            "Merci d’utiliser nos services de gestion de tension artérielle et nos services OMRON connect.\n" .
            "Veuillez trouver ci-joint l’export du carnet de bord de tension artérielle.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Cordialement,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Droits d’auteur (c) 2016 OMRON HEALTHCARE Co., Ltd.  Tous droits réservés.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Cet email vous a été envoyé à partir d’une adresse de notification qui ne peut pas recevoir de courrier électronique. Merci de ne pas répondre à ce message.\n" .
            '*Si vous n’avez pas utilisé nos services ou si le nom d’utilisateur indiqué n’est pas le vôtre, veuillez transférer cet email à «tpl_mail».' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Carnet de bord de tension artérielle ( Moy. des tensions artérielles du matin et du soir )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Moyenne/semaine',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'd/m/Y',
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'fr_FR.UTF-8',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'j/n/Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Nom: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Date',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_DATES' => ['lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.', 'dim.'],
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['jan', 'fév', 'mar', 'avr', 'mai', 'juin', 'juil','aoû','sep','oct','nov','déc'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Tension artérielle',
        'PDF_TIME' => 'Heure',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Consultation',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'Traitement',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Remarques',
        'PDF_NOTES2' => 'Remarques',
        'PDF_PERIOD' => 'Période',
        'PDF_AVG' => 'Jours de mesure/semaine',
        'PDF_AVG_PERIOD' => 'Moyenne/période',
        'PDF_AVG_TEXT' => 'Moyenne',
        'PDF_DATE_CREATE' => 'Fichier PDF créé le: ',
        'PDF_FOOTER_LINE1' => 'À l’attention des professionnels de santé',
        'PDF_FOOTER_LINE2' => '* Ce graphique comporte uniquement les mesures de tension artérielle aux heures de lever et de coucher suivant les recommandations des hypertenseurs.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Tension artérielle et pouls du matin',
        'PDF_FOOTER_LINE4' => 'Tension artérielle et pouls du soir',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Si le nombre de mesures est inférieur à trois, la moyenne sera calculée d’après le nombre de mesures disponible.',
        'PDF_TITLE_CSV' => 'Date de mesure,Fuseau horaire,SYS,DIA,Pulse,Nom du modèle de l’appareil',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'Votre valeur SYS cible',
        'PDF_YOUR_TARGET_DIA' => 'Votre valeur DIA cible',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Objectif atteint',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Valeur cible atteinte',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Égale ou légèrement',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'supérieure',
        'PDF_HIGHER' => 'Supérieure',
        'PDF_MUCH_HIGHER' => 'Nettement supérieure',
        'PDF_FOOTER_LINE3_2' => 'La moyenne des 3 premières mesures prises en l’espace de 10 minutes entre mor_start et mor_end.',
        'PDF_FOOTER_LINE4_2' => 'La moyenne des 3 dernières mesures prises en l’espace de 10 minutes entre eve_start et eve_end.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Carnet de bord de tension artérielle ( Moy. quotidienne )',
        'PDF_DAY_AVG_FOOTER_1' => 'Moyenne quotidienne',
        'PDF_DAY_AVG_FOOTER_2' => 'La moyenne de toutes les mesures prises en un jour.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Carnet de bord de tension artérielle ( Liste )',
        'PDF_WEEK_AVG_NAME' => 'Nom',
        'PDF_WEEK_AVG_HEADER' => 'Moyenne hebdomadaire',
        'PDF_WEEK_PERIOD' => 'Période',
        'PDF_WEEK_MOR_AVG' => 'matin moyenne',
        'PDF_WEEK_EVE_AVG' => 'soirée moyenne',
        'PDF_WEEK_DAY_AVG' => 'Moy. quotidienne',
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Toutes les mesures',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Mode, Infos détectées (I.D.)',
        'PDF_WEEK_HINT_HEADER' => 'Infos détectées (I.D.)',
        'PDF_WEEK_HIND_NOTES' => 'Icône mémo',
        'PDF_WEEK_HINT_MODE' => 'Mode',
        'PDF_WEEK_HINT_MODE_DES_1' => "S’affiche lorsque la mesure se fait en mode Afib.",
        'PDF_WEEK_HINT_MODE_DES_2' => "",
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Mode',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => "S’affiche lorsque la mesure se fait en mode nocturne.",
        // BP_APP_DEV-1271 2020.04.21 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Erreur de mesure',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => "S'affiche lorsque l'erreur Indicateur de position, Guide",
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => "d'enroulement du brassard ou Mouvement corporel",
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'est détectée lors de la mesure.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Pulsations cardiaques irrégulières',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Un rythme irrégulier a été détecté à deux reprises ou plus',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'pendant une mesure.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => "S'affiche lorsqu'une possible fibrillation auriculaire (AFib) est",
        'PDF_WEEK_HINT_AFIB_DESC_2' => "détectée pendant la mesure.",
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Exercice physique',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Pas d\'exercice',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Sel réduit',
        'PDF_WEEK_HINT_SALT' => 'Sel',
        'PDF_WEEK_HINT_VEGETABLES' => 'Légumes',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Moins de légumes ',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Moins d\'alcool',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alcool',
        'PDF_WEEK_HINT_SLEEP' => 'Sommeil ',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Abs de sommeil',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Aucune cigarette',
        'PDF_WEEK_HINT_SMOKING' => 'Cigarette',
        'PDF_WEEK_HINT_CONSULTATION' => 'Consultation',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Infos détectées (I.D.)',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Date',
        'PDF_WEEK_TIME' => 'Heure',
        'PDF_WEEK_DI' => 'I.D.',
        'PDF_WEEK_MED' => 'Tra.',
        'PDF_WEEK_NOTES' => 'Remarques',
        'PDF_PAGE'  => 'Page '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'IT' => [
        'title' => '[OMRON connect]Invio del tuo Registro della pressione sanguigna in corso',
        'body' =>
            "Gentile tpl_user,\n\n" .
            "Grazie per aver utilizzato i nostri servizi OMRON connect e Blood Pressure Management.\n" .
            "Allegato a questa e-mail troverai il Registro della pressione sanguigna esportato.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Cordiali Saluti,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  Tutti i diritti riservati.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Questo messaggio e-mail è stato inviato da un indirizzo di sola notifica che non può accettare e-mail in arrivo. Non rispondere a questo messaggio.\n" .
            '*Se non hai utilizzato il nostro servizio o se il nome utente non è tuo, inoltra questa email a “tpl_mail”.' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Registro della pressione sanguigna ( pressione sanguigna mattutina e serale media )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Media/sett.',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'j/n/Y',
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'it_IT',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        // 改造BP_APP_DEV-1103 2019.09.17 TanND ---->
        'PDF_DAY_FORMAT' => 'j/n/Y',
        // BP_APP_DEV-1103 2019.09.17 <----
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Nome: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Data',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['lun', 'mar', 'mer', 'gio', 'ven', 'sab', 'dom'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug','ago','set','ott','nov','dic'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Pressione sanguigna',
        'PDF_TIME' => 'Ora',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Consultazione',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'Farmaco',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Annotazioni',
        'PDF_NOTES2' => 'Annotazioni',
        'PDF_PERIOD' => 'Zeitraum',
        'PDF_AVG' => 'Gg di misurazione/sett.',
        'PDF_AVG_PERIOD' => 'Media/periodo',
        'PDF_AVG_TEXT' => 'Media',
        'PDF_DATE_CREATE' => 'PDF creato il: ',
        'PDF_FOOTER_LINE1' => 'All’attenzione dello specialista medico',
        'PDF_FOOTER_LINE2' => '* Questo grafico è tracciato solo con i valori della pressione sanguigna misurati al risveglio mattutino e prima di coricarsi secondo le linee guida per il trattamento dell’ipertensione.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Mattutine pressione sanguigna e pulsazioni ',
        'PDF_FOOTER_LINE4' => 'Serata pressione sanguigna e pulsazioni ',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Se il numero di misurazioni è inferiore a tre, la media sarà calcolata in base al numero di volte in cui sono effettuate le misurazioni.',
        'PDF_TITLE_CSV' => 'Data misurazione,Fuso orario,SYS,DIA,Pulse,Nome modello dispositivo',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'SYS target',
        'PDF_YOUR_TARGET_DIA' => 'DIA target',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Livello di successo',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Obiettivo raggiunto',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Uguale o leggermente',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'più alta',
        'PDF_HIGHER' => 'Più alta',
        'PDF_MUCH_HIGHER' => 'Molto più alta',
        'PDF_FOOTER_LINE3_2' => 'La media delle prime 3 letture acquisite entro 10 minuti tra le mor_start e le mor_end.',
        'PDF_FOOTER_LINE4_2' => 'La media delle ultime 3 letture acquisite entro 10 minuti tra le eve_start e le eve_end.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Registro della pressione sanguigna ( Media giornaliera )',
        'PDF_DAY_AVG_FOOTER_1' => 'Media giornaliera',
        'PDF_DAY_AVG_FOOTER_2' => 'La media di tutte le misurazioni di dati in un giorno.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Registro della pressione sanguigna ( Elenco )',
        'PDF_WEEK_AVG_NAME' => 'Nome',
        'PDF_WEEK_AVG_HEADER' => 'Media settimanale',
        'PDF_WEEK_PERIOD' => 'Periodo',
        'PDF_WEEK_MOR_AVG' => 'Mattina media',
        'PDF_WEEK_EVE_AVG' => 'Serata media',
        'PDF_WEEK_DAY_AVG' => 'Media giornaliera',
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Tutte le letture',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Modo, Informazioni rilevate (I.R.)',
        'PDF_WEEK_HINT_HEADER' => 'Informazioni rilevate (I.R.)',
        'PDF_WEEK_HIND_NOTES' => 'Icona Memo',
        'PDF_WEEK_HINT_MODE' => 'Modo',
        'PDF_WEEK_HINT_MODE_2' => 'Modalità',
        'PDF_WEEK_HINT_MODE_DES' => 'Visualizzato quando si misura in modalità Afib.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Modalità',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Visualizzato quando si misura in modalità notturna.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // 改造BP_APP_DEV-1103 2019.09.18 VinhHP ---->
        'PDF_WEEK_HINT_MODE_DES_1' => 'Visualizzato quando si misura in modalità Afib.',
        'PDF_WEEK_HINT_MODE_DES_2' => '',
        // BP_APP_DEV-1103 2019.09.18 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Errore di misurazione',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Visualizzato quando durante la misurazione viene rilevato un errore',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'in Indicatore di posizionamento, Guida posizionamento bracciale o ',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'Sensore di movimento.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Battito cardiaco irregolare',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'È stato rilevato un ritmo irregolare due o più volte durante una',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'misurazione.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Viene visualizzato quando viene rilevata una possibile fibrillazione',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'atriale (AFib) durante la misurazione.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Attività fisica',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Nessun esercizio',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Sale ridotto',
        'PDF_WEEK_HINT_SALT' => 'Sale',
        'PDF_WEEK_HINT_VEGETABLES' => 'Verdure',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Poche verdure',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Nessun alcolico',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alcolici',
        'PDF_WEEK_HINT_SLEEP' => 'Riposo',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Assenza di sonno',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Niente fumo',
        'PDF_WEEK_HINT_SMOKING' => 'Fumo',
        'PDF_WEEK_HINT_CONSULTATION' => 'Consultazione',
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Informazioni rilevate (I.R.)',
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Data',
        'PDF_WEEK_TIME' => 'Ora',
        'PDF_WEEK_DI' => 'I.R.',
        'PDF_WEEK_MED' => 'Farm.',
        'PDF_WEEK_NOTES' => 'Annotazioni',
        'PDF_PAGE'  => 'Pagina ',
        // BP_APP_DEV-1059 2018.08.23 <----

    ],
    'ES' => [
        'title' => '[OMRON connect]Envío del registro de tensión arterial',
        'body' =>
            "Hola, tpl_user:\n\n" .
            // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
            "Gracias por utilizar OMRON connect y nuestros servicios de control de presión arterial.\n" .
            "Encuentra el registro de presión arterial exportado como archivo adjunto en este correo electrónico.\n" .
            // 改造BP_APP_DEV-2678 2023.06.01 <----
            "\n" .
            "tpl_filename\n" .
            "\n" .
            // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
            'Saludos,' . "\n" .
            // 改造BP_APP_DEV-2678 2023.06.01 <----
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd. Todos los derechos reservados.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Este mensaje de correo electrónico se ha enviado desde una dirección solo para notificaciones y no puede aceptar un correo entrante. No responda a este mensaje.\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        // 改造BP_APP_DEV-2889 2023.06.21 TrungNT ---->
        'PDF_TITLE' => 'Registro de presión arterial (Promedio Día y Noche)',
        // 改造BP_APP_DEV-2889 2023.06.21 <----
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Media semanal',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'd/m/Y',
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'es_ES',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'd/m/Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Nombre: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Fecha',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['lun', 'mar', 'mié', 'jue', 'vie', 'sáb', 'dom'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul','ago','sep','oct','nov','dic'],
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_BP' => 'Presión arterial',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_TIME' => 'Hora',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Consulta',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_MEDICATION' => 'Medicamento',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Notas',
        'PDF_NOTES2' => 'Notas',
        'PDF_PERIOD' => 'Zeitraum',
        'PDF_AVG' => 'Días con mediciones/semana',
        'PDF_AVG_PERIOD' => 'Media/periodo',
        'PDF_AVG_TEXT' => 'Media',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_DATE_CREATE' => 'Crear PDF: ',
        'PDF_FOOTER_LINE1' => 'Para profesionales de la salud',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_FOOTER_LINE2' => '* Este gráfico se genera únicamente a partir de los valores de tensión arterial obtenidos a la hora de levantarse y de acostarse, tal y como establecen las directrices para el tratamiento de la hipertensión.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3' => 'Mañana presión arterial y pulso',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_FOOTER_LINE4' => 'Noche presión arterial y pulso',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Si el número de mediciones es inferior a tres, la medida se calcula a partir del número de veces que se ha realizado la medición.',
        // 改造BP_APP_DEV-2939 2023.06.21 TrungNT ---->
        'PDF_TITLE_CSV' => 'Fecha de medición,Zona horaria,SYS,DIA,Pulso,Nombre del modelo de dispositivo',
        // 改造BP_APP_DEV-2939 2023.06.21 <----
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_LESSTHAN_TEXT' => 'Menos de',
        'PDF_YOUR_TARGET_SYS' => 'Su presión SYS objetivo',
        'PDF_YOUR_TARGET_DIA' => 'Su presión DIA objetivo',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Nivel de logro',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Objetivo conseguido',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Igual o ligeramente',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'superior',
        'PDF_HIGHER' => 'Superior',
        'PDF_MUCH_HIGHER' => 'Muy superior',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'La media de las primeras 2 lecturas obtenidas durante un periodo de 10 minutos entre las mor_start y las mor_end h.',
        'PDF_FOOTER_LINE4_2' => 'La media de las últimas 2 lecturas obtenidas durante un periodo de 10 minutos entre las eve_start y las eve_end h.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_DAY_AVG_TITLE' => 'Registro de presión arterial (Media diaria )',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_DAY_AVG_FOOTER_1' => 'Media diaria',
        'PDF_DAY_AVG_FOOTER_2' => 'La media de todas las mediciones de datos obtenidas durante un día.',
        //WEEK AVG
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_WEEK_AVG_TITLE' => 'Registro de presión arterial ( Lista )',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_WEEK_AVG_NAME' => 'Nombre',
        'PDF_WEEK_AVG_HEADER' => 'Promedio semanal',
        'PDF_WEEK_PERIOD' => 'Periodo',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_MOR_AVG' => 'Promedio de mañana',
        'PDF_WEEK_EVE_AVG' => 'Promedio de la tarde',
        'PDF_WEEK_DAY_AVG' => 'Media diaria',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Todas las lecturas',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Modo, Información detectada (I.D.)',
        'PDF_WEEK_HINT_HEADER' => 'Información detectada (I.D.)',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_WEEK_HIND_NOTES' => 'Ícono de nota',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_WEEK_HINT_MODE' => 'Modo',
        'PDF_WEEK_HINT_MODE_DES' => 'Aparece al medir en modo de Afib.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Modo',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Aparece al medir en modo nocturno.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Error de medición',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Aparece cuando se detecta un error en el indicador de posición,',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'en la guía de ajuste del brazalete o cuando se detecta',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'movimiento corporal durante la medición.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Latido arrítmico',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Se ha detectado un ritmo irregular en dos o más ocasiones',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'durante una medición.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.14 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Se muestra cuando se detecta una posible fibrilación auricular',
        'PDF_WEEK_HINT_AFIB_DESC_2' => '(AFib) durante la medición.',
        // 改造BP_APP_DEV-4599 2025.04.14 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Ejercicio',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'No ejercicio',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Sal reducida',
        'PDF_WEEK_HINT_SALT' => 'Sal',
        'PDF_WEEK_HINT_VEGETABLES' => 'Verdura',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Poca verdura',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Sin alcohol',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alcohol',
        'PDF_WEEK_HINT_SLEEP' => 'Sueño ',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Falta de sueño',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Sin tabaco',
        'PDF_WEEK_HINT_SMOKING' => 'Con tabaco',
        // 改造BP_APP_DEV-2960 2023.06.23 TrungNT ---->
        'PDF_WEEK_HINT_CONSULTATION' => 'Hospital',
        // 改造BP_APP_DEV-2960 2023.06.23 <----
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Información detectada (I.D.)',
        // BP_APP_DEV-1059 2019.09.17 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----

        'PDF_WEEK_DATE' => 'Fecha',
        'PDF_WEEK_TIME' => 'Hora',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_DI' => 'I.D.',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_MED' => 'Med.',
        'PDF_WEEK_NOTES' => 'Notas',
        'PDF_PAGE'  => 'Página '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'NL' => [
        'title' => '[OMRON connect]Bezig met verzenden van uw bloeddruklogboek',
        'body' =>
            "Geachte tpl_user,\n\n" .
            "Bedankt dat u OMRON connect en onze bloeddrukbewakingsdiensten gebruikt.\n" .
            "Bijgesloten bij deze e-mail vindt u het geëxporteerde bloeddruklogboek.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Hoogachtend,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd. Alle rechten voorbehouden.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Dit e-mailbericht is aan u verzonden vanaf een alleen voor uitgaande meldingen bestemd e-mailadres dat geen inkomende e-mailberichten kan ontvangen. Stuur geen antwoord op dit bericht.\n" .
            '*Als u onze service niet hebt gebruikt of als deze gebruikersnaam niet van u is, vragen we u dit e-mailbericht door te sturen naar tpl_mail.' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Bloeddruklogboek ( Gem. bloeddruk ochtend en avond )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Gemiddelde/week',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'd/m/Y',
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'nl_NL',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'd/m/Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Naam: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Datum',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['ma', 'di', 'wo', 'do', 'vr', 'za', 'zo'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['jan', 'febr', 'maa', 'apr', 'mei', 'jun', 'jul','aug','sep','okt','nov','dec'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Bloeddruk',
        'PDF_TIME' => 'Tijd',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Overleg',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'Medicijn',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Opmerkingen',
        'PDF_NOTES2' => 'Opmerkingen',
        'PDF_PERIOD' => 'Zeitraum',
        'PDF_AVG' => 'Dagen gemeten/week',
        'PDF_AVG_PERIOD' => 'Gemiddelde/periode',
        'PDF_AVG_TEXT' => 'Gem',
        'PDF_DATE_CREATE' => 'PDF maken voor: ',
        'PDF_FOOTER_LINE1' => 'Voor medische beroepsbeoefenaars',
        'PDF_FOOTER_LINE2' => '* Deze grafiek wordt uitsluitend samengesteld uit bloeddrukwaarden bij opstaan en naar bed gaan, volgens de richtlijnen voor het behandelen van hoge bloeddruk.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Ochtendbloeddruk en pols',
        'PDF_FOOTER_LINE4' => 'Avondbloeddruk en pols',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Als het aantal metingen minder dan drie is, wordt het gemiddelde berekend op basis van het aantal keren dat gemeten is.',
        'PDF_TITLE_CSV' => 'Datum van meting,Tijdzone,SYS,DIA,Pulse,Modelnaam apparaat',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'Uw doel-SYS',
        'PDF_YOUR_TARGET_DIA' => 'Uw doel-DIA',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Bereikte niveau',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Doel bereikt',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Gelijk of iets',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'hoger',
        'PDF_HIGHER' => 'Hoger',
        'PDF_MUCH_HIGHER' => 'Veel hoger',
        'PDF_FOOTER_LINE3_2' => 'Het gemiddelde van de eerste 3 waarden die met een interval van 10 minuten worden gemeten tussen mor_start en mor_end.',
        'PDF_FOOTER_LINE4_2' => 'Het gemiddelde van de laatste 3 waarden die met een interval van 10 minuten worden gemeten tussen eve_start en eve_end.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Bloeddruklogboek ( Daggemiddelde )',
        'PDF_DAY_AVG_FOOTER_1' => 'Daggemiddelde',
        'PDF_DAY_AVG_FOOTER_2' => 'Het gemiddelde van alle gegevensmetingen op één dag.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Bloeddruklogboek ( Lijst )',
        'PDF_WEEK_AVG_NAME' => 'Naam',
        'PDF_WEEK_AVG_HEADER' => 'Weekgemiddelde',
        'PDF_WEEK_PERIOD' => 'Periode',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_MOR_AVG' => 'Ochtendgemiddelde',
        'PDF_WEEK_EVE_AVG' => 'Avondgemiddelde',
        'PDF_WEEK_DAY_AVG' => 'Daggemiddelde',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Alle meetwaarden',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Modus, Waargenomen info (W.I.)',
        'PDF_WEEK_HINT_HEADER' => 'Waargenomen info (W.I.)',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_HIND_NOTES' => 'Memopictogram',
        'PDF_WEEK_HINT_MODE' => 'Modus',
        'PDF_WEEK_HINT_MODE_DES' => 'Verschijnt bij meting in de Afib-modus.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Modus',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Verschijnt bij meting in de nachtelijke modus.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Meetfout',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Verschijnt wanneer tijdens de meting een fout wordt',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'gedetecteerd in de Positie-indicator, Manchetinstelling of',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'Lichaamsbeweging.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Onregelmatige hartslag',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Er is twee keer of meer een onregelmatig ritme waargenomen',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'tijdens een meting.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Wordt weergegeven wanneer mogelijk boezemfibrilleren (AFib)',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'tijdens de meting wordt gedetecteerd.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Beweging',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Geen oefening',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Gereduceerd zout',
        'PDF_WEEK_HINT_SALT' => 'Zout',
        'PDF_WEEK_HINT_VEGETABLES' => 'Groenten',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Minder groenten',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Geen alcohol',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alcohol',
        'PDF_WEEK_HINT_SLEEP' => 'Genoeg slaap',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Te weinig slaap',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Niet roken',
        'PDF_WEEK_HINT_SMOKING' => 'Roken',
        'PDF_WEEK_HINT_CONSULTATION' => 'Overleg',
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Waargenomen info (W.I.)',
        // BP_APP_DEV-1059 2019.09.17 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Datum',
        'PDF_WEEK_TIME' => 'Tijd',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_DI' => 'W.I.',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_MED' => 'Med.',
        'PDF_WEEK_NOTES' => 'Opmerkingen',
        'PDF_PAGE'  => 'Pagina '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    // 改造BP_APP_DEV-1038 2019.05.30 TranHV ---->
    'PL' => [
        'title' => '[OMRON connect]Przesyłanie dziennika ciśnienia krwi',
        'body' =>
            "Witaj tpl_user,\n\n" .
            "Dziękujemy za skorzystanie z naszych usług OMRON connect i zarządzania ciśnieniem krwi.\n" .
            "Do niniejszej wiadomości e-mail dołączono wyeksportowany dziennik ciśnienia krwi.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Z poważaniem,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  Wszelkie prawa zastrzeżone.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "* Niniejsza wiadomość e-mail została wysłana z adresu przeznaczonego wyłącznie do powiadomień, który nie obsługuje wiadomości przychodzących. Prosimy nie odpowiadać na tę wiadomość.\n" .
            '* Jeśli nie korzystał(-a) Pan/Pani z naszych usług lub jeśli dana nazwa użytkownika nie należy do Pana/Pani, prosimy o skierowanie tej wiadomości e-mail na adres „ogsc_support@ssa.omron.co.jp”.' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Dziennik ciśnienia krwi (Średnie poranne i wieczorne ciśnienie krwi )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Średnio/tyg.',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        // 改造BP_APP_DEV-1076 2019.06.17 TranHV ---->
        'PDF_DATE_FORMAT' => 'd.m.Y',
        // BP_APP_DEV-1076 2019.06.17 <----
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'pl_PL',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'd.m.Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Nazwa: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Data',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['Pn','Wt','Śr','Cz','Pt','Sb', 'Nd'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => [ 'Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Cze','Lip','Sie','Wrz','Paź','Lis','Gru'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Ciśnienie krwi',
        'PDF_TIME' => 'Czas',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Konsultacje',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'Lek',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Uwagi',
        'PDF_NOTES2' => 'Uwagi',
        'PDF_PERIOD' => 'Okres',
        // 改造BP_APP_DEV-1072 2019.06.21 ThanhDKN ---->
        'PDF_AVG' => 'Dni pomiarów/tyg.',
        // BP_APP_DEV-1072 2019.06.21 <----
        'PDF_AVG_PERIOD' => 'Średnio/okres',
        'PDF_AVG_TEXT' => 'Śr.',
        'PDF_DATE_CREATE' => 'Utwórz PDF na: ',
        'PDF_FOOTER_LINE1' => 'Dla pracowników służby zdrowia',
        'PDF_FOOTER_LINE2' => '* Niniejszy wykres jest tworzony wyłącznie przy wykorzystaniu wartości ciśnienia krwi uzyskanych po przebudzeniu i przed zaśnięciem zgodnie z wytycznymi dotyczącymi leczenia nadciśnienia.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Poranne ciśnienie krwi i puls',
        'PDF_FOOTER_LINE4' => 'Wieczorne ciśnienie krwi i puls',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Jeśli liczba przeprowadzonych pomiarów nie przekracza trzech, średnia zostanie obliczona na podstawie liczby wykonanych pomiarów.',
        'PDF_TITLE_CSV' => 'Data pomiaru,Strefa czasowa,SYS,DIA,Pulse,Nazwa modelu urządzenia',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'Docelowa wartość SYS',
        'PDF_YOUR_TARGET_DIA' => 'Docelowa wartość DIA',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Poziom osiągnięcia',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Pomiar w porównaniu z ciśnieniem docelowym',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Równy lub nieco',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'wyższy',
        'PDF_HIGHER' => 'Wyższy',
        'PDF_MUCH_HIGHER' => 'Znacznie wyższy',
        'PDF_FOOTER_LINE3_2' => 'Średnia z pierwszych 3 odczytów wykonanych w ciągu 10 minut między godziną mor_start a mor_end.',
        'PDF_FOOTER_LINE4_2' => 'Średnia z ostatnich 3 odczytów wykonanych w ciągu 10 minut między eve_start a eve_end.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Dziennik ciśnienia krwi (Dzienna średnia )',
        'PDF_DAY_AVG_FOOTER_1' => 'Dzienna średnia',
        'PDF_DAY_AVG_FOOTER_2' => 'Średnia wszystkich pomiarów danych w ciągu dnia.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Dziennik ciśnienia krwi ( Lista )',
        'PDF_WEEK_AVG_NAME' => 'Nazwa',
        'PDF_WEEK_AVG_HEADER' => 'Średnia tygodniowa',
        'PDF_WEEK_PERIOD' => 'Okres',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_MOR_AVG' => 'Średnia rano',
        'PDF_WEEK_EVE_AVG' => 'Średnia wieczór',
        'PDF_WEEK_DAY_AVG' => 'Dzienna średnia',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Wszystkie odczyty',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Tryb, Wykryte informacje (W.I.)',
        'PDF_WEEK_HINT_HEADER' => 'Wykryte informacje (W.I.)',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_HIND_NOTES' => 'Ikona Memo',
        'PDF_WEEK_HINT_MODE' => 'Tryb',
        'PDF_WEEK_HINT_MODE_DES_1' => 'Pojawia się podczas pomiaru w trybie Afib.',
        'PDF_WEEK_HINT_MODE_DES_2' => '',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Tryb',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Pojawia się podczas pomiaru w trybie nocnym.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Błąd pomiaru',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Wyświetlana, gdy podczas pomiaru wykryto błąd w funkcji',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'Wskaźnik pozycji, Instrukcja dotycząca mankietu lub Ruch ciała.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Nieregularne bicie serca',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Pojawia się w razie wykrycia nieregularnego rytmu co najmniej',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'dwukrotnie podczas pomiaru.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Wyświetla się, gdy podczas pomiaru wykryte zostanie możliwe',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'migotanie przedsionków (AFib).',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Ćwiczenia',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Brak ćwiczeń',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Ograniczenie soli',
        'PDF_WEEK_HINT_SALT' => 'Sól',
        'PDF_WEEK_HINT_VEGETABLES' => 'Warzywa',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Mniej warzyw',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Bez alkoholu',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alkohol',
        'PDF_WEEK_HINT_SLEEP' => 'Sen',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Brak snu',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Brak palenia',
        'PDF_WEEK_HINT_SMOKING' => 'Palenie',
        'PDF_WEEK_HINT_CONSULTATION' => 'Konsultacje',
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Wykryte informacje (W.I.)',
        // BP_APP_DEV-1059 2019.09.17 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Data',
        'PDF_WEEK_TIME' => 'Czas',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_DI' => 'W.I.',
        'PDF_WEEK_MED' => 'Lek',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_NOTES' => 'Uwagi',
        'PDF_PAGE'  => 'Strona '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'SV' => [
        'title' => '[OMRON connect]Skickar din blodtrycksloggbok',
        'body' =>
            "Hej tpl_user,\n\n" .
            "Tack för att du använder våra OMRON Connect- och blodtryckstjänster.\n" .
            "Din exporterade blodtrycksloggbok bifogas detta e-postmeddelande.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Vänliga hälsningar,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Upphovsrätt (c) 2016 OMRON HEALTHCARE Co., Ltd.  Alla rättigheter förbehålls.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Det här e-postmeddelandet skickades från en adress som inte kan ta emot inkommande e-post. Svara inte på det här meddelandet.\n" .
            '*Om du inte har använt vår tjänst eller om användarnamnet inte tillhör dig, ber vi dig vidarebefordra detta e-postmeddelande till tpl_mail.' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Blodtrycksloggbok ( Morgonblodtryck & Kvällsblodtryck Genomsnitt )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Genomsnitt/vecka',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'd/m/Y',
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'sv_SE',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'd/m/Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Namn: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Datum',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['Mån','Tis','Ons','Tor','Fre','Lör', 'Sön'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul','Aug','Sep','Okt','Nov','Dec'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Blodtryck',
        'PDF_TIME' => 'Tid',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Rådgivning',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'Medicinering',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Anteckningar',
        'PDF_NOTES2' => 'Anteckningar',
        'PDF_PERIOD' => 'Period',
        'PDF_AVG' => 'Dagar som mäts/vecka',
        'PDF_AVG_PERIOD' => 'Genomsnitt/period',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_AVG_TEXT' => 'Genomsnitt',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_CREATE' => 'Skapa PDF på: ',
        'PDF_FOOTER_LINE1' => 'För vårdpersonal',
        'PDF_FOOTER_LINE2' => '* Diagrammet innehåller endast blodtrycksvärden medan patienten är vaken och vid läggdags enligt behandlingsriktlinjerna för hypertoni.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Morgon blodtryck och puls',
        'PDF_FOOTER_LINE4' => 'Kvällens blodtryck och puls',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Om antalet mätningar är lägre än tre, beräknas medelvärdet baserat på antalet gånger som mäts.',
        // 改造BP_APP_DEV-1076 2019.06.12 TranHV ---->
        'PDF_TITLE_CSV' => 'Mätdatum,Tidszon,SYS,DIA,Pulse,Enhetens modellnamn',
        // BP_APP_DEV-1076 2019.06.12 <----
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'Ditt SYS-mål',
        'PDF_YOUR_TARGET_DIA' => 'Ditt DIA-mål',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Prestationsnivån',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Mål uppnått',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Lika eller något',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'högre',
        'PDF_HIGHER' => 'Högre',
        'PDF_MUCH_HIGHER' => 'Mycket högre',
        'PDF_FOOTER_LINE3_2' => 'Genomsnittet av de första 3 avläsningarna inom 10 minuter mellan mor_start och mor_end.',
        'PDF_FOOTER_LINE4_2' => 'Genomsnittet av de första 3 avläsningarna inom 10 minuter mellan eve_start och eve_end.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Blodtrycksloggbok ( Dagligt genomsnitt )',
        'PDF_DAY_AVG_FOOTER_1' => 'Dagligt genomsnitt',
        'PDF_DAY_AVG_FOOTER_2' => 'Medelvärdet av alla datamätningar under en dag.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Blodtrycksloggbok ( Lista )',
        'PDF_WEEK_AVG_NAME' => 'Namn',
        'PDF_WEEK_AVG_HEADER' => 'Genomsnitt per vecka',
        'PDF_WEEK_PERIOD' => 'Period',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_MOR_AVG' => 'Morning Average',
        'PDF_WEEK_EVE_AVG' => 'Evening Average',
        'PDF_WEEK_DAY_AVG' => 'Dagligt genomsnitt',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Alla avläsningar',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Läge, Upptäckt info (U.I.)',
        'PDF_WEEK_HINT_HEADER' => 'Upptäckt info (U.I.)',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_HIND_NOTES' => 'Memo-ikon',
        'PDF_WEEK_HINT_MODE' => 'Läge',
        'PDF_WEEK_HINT_MODE_DES' => 'Visas vid mätning i Afib-läge.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Läge',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Visas vid mätning i nattläge.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Mätfel',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Visas när fel har uppstått som kopplas till',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'positioneringsindikatorn, manschettanpassning',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'eller om kroppen rör sig under mätningen.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Oregelbunden hjärtrytm',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'En oregelbunden rytm upptäcks två eller fler gånger under',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'en mätning.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Visas när möjlig förmaksflimmer (AFib) upptäcks under',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'mätningen.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Motion',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Otillräcklig motion',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Begränsa salt',
        'PDF_WEEK_HINT_SALT' => 'Salt',
        'PDF_WEEK_HINT_VEGETABLES' => 'Grönsaker',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Mindre grönsaker',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Ingen alkohol',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alkohol',
        'PDF_WEEK_HINT_SLEEP' => 'Sömn',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Sömnbrist',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Ingen rökning',
        'PDF_WEEK_HINT_SMOKING' => 'Rökning',
        'PDF_WEEK_HINT_CONSULTATION' => 'Rådgivning',
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Upptäckt info (U.I.)',
        // BP_APP_DEV-1059 2019.09.17 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Datum',
        'PDF_WEEK_TIME' => 'Tid',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_DI' => 'U.I.',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_MED' => 'Med.',
        'PDF_WEEK_NOTES' => 'Anteckningar',
        'PDF_PAGE'  => 'Sida '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'TR' => [
        'title' => '[OMRON connect]BP Kayıt Defteriniz Gönderiliyor',
        'body' =>
            "Sayın tpl_user,\n\n" .
            "OMRON connect ve Tansiyon Yönetimi hizmetlerimizi kullandığınız için teşekkür ederiz.\n" .
            "Bu e-posta ekinde dışa aktarılan Tansiyon Kayıt Defterini bulabilirsiniz.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            'Saygılarımızla,' . "\n" .
            "\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Telif Hakkı (c) 2016 OMRON HEALTHCARE Co., Ltd.  Tüm Hakları Saklıdır.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Bu e-posta iletisi, gelen e-postaları kabul edemeyen, yalnızca bildirime yönelik bir adresten gönderilmiştir. Bu iletiyi yanıtlamayın.\n" .
            '*Hizmetimizi kullanmadıysanız veya kullanıcı adı size ait değilse lütfen bu e-postayı “ogsc_support@ssa.omron.co.jp” adresine iletin.' . "\n",

        // constants for pdf template send mail
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_TITLE' => 'Tansiyon Kayıt Defteri ( Sabah ve Akşam Tansiyonu Ort. )',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_TITLE_SUB' => ' Ortalama/hf.',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        // 改造BP_APP_DEV-1076 2019.06.17 TranHV ---->
        'PDF_DATE_FORMAT' => 'd.m.Y',
        // BP_APP_DEV-1076 2019.06.17 <----
        'PDF_MONTH_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'tr_TR',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_WEEK_FORMAT' => 'j/n',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_DAY_FORMAT' => 'd.m.Y',
        // BP_APP_DEV-1059 2018.08.23 <----
        'PDF_DATE_SEPARATE' => '~',
        'PDF_NAME' => 'Adı: ',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_DATE' => 'Tarih',
        // BP_APP_DEV-1059 2019.09.05 <----
        // 改造BP_APP_DEV-901 2018.12.19 TanND ---->
        'PDF_DATES' => ['Pzt','Sal','Çar','Per','Cum','Cts', 'Pz'],
        // BP_APP_DEV-901 2018.12.19 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MONTH' => ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem','Ağu','Eyl','Eki','Kas','Ara'],
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_BP' => 'Tansiyon',
        'PDF_TIME' => 'Saat',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_CONSULTATION' => 'Konsültasyon',
        // BP_APP_DEV-1059 2018.08.23 <----
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_MEDICATION' => 'İlaç',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_NOTES' => 'Notlar',
        'PDF_NOTES2' => 'Notlar',
        'PDF_PERIOD' => 'Dönem',
        'PDF_AVG' => 'Ölçüm yapılan gün/hf.',
        'PDF_AVG_PERIOD' => 'Ortalama/dönem',
        'PDF_AVG_TEXT' => 'Ort',
        'PDF_DATE_CREATE' => 'PDF oluştur: ',
        'PDF_FOOTER_LINE1' => 'Sağlık mesleği mensuplarına',
        'PDF_FOOTER_LINE2' => '* Bu grafik yalnızca hipertansif tedavi kılavuzları uyarınca uyanma saati ve yatma saati dolaylarındaki tansiyon değerleriyle çizilmiştir.',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_FOOTER_LINE3' => 'Sabah tansiyonu ve nabzı',
        'PDF_FOOTER_LINE4' => 'Akşam tansiyonu ve nabzı',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_FOOTER_LINE5' => 'Ölçümlerin sayısı üçten azsa, ortalama ölçüm sayısına göre hesaplanacaktır.',
        'PDF_TITLE_CSV' => 'Ölçüm Tarihi,Saat Dilimi,SYS,DIA,Pulse,Cihaz Modeli Adı',
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        'PDF_YOUR_TARGET_SYS' => 'Hedef SYS değeriniz',
        'PDF_YOUR_TARGET_DIA' => 'Hedef DIA değeriniz',
        // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
        'PDF_ACHIEVEMENT_LEVEL' => 'Başarı seviyesini',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_TARGET_ACHIEVED' => 'Ulaşılan Hedef',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Eşit veya Biraz',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'Daha Yüksek',
        'PDF_HIGHER' => 'Daha Yüksek',
        'PDF_MUCH_HIGHER' => 'Çok Daha Yüksek',
        'PDF_FOOTER_LINE3_2' => 'mor_start ile mor_end arasında 10 dakika içinde alınan ilk 3 ölçümün ortalaması.',
        'PDF_FOOTER_LINE4_2' => 'eve_start ile eve_end arasında 10 dakika içinde alınan son 3 ölçümün ortalaması.',
        'PDF_NEXT_DAY'      => '',
        //Need to update for this language
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Tansiyon Kayıt Defteri ( Günlük Ort. )',
        'PDF_DAY_AVG_FOOTER_1' => 'Günlük Ortalama',
        'PDF_DAY_AVG_FOOTER_2' => 'Bir gün içindeki tüm ölçümlerin ortalaması.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Tansiyon Kayıt Defteri ( Liste )',
        'PDF_WEEK_AVG_NAME' => 'Adı',
        'PDF_WEEK_AVG_HEADER' => 'Haftalık Ortalama',
        'PDF_WEEK_PERIOD' => 'Dönem',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_MOR_AVG' => 'Sabah Ortalama',
        'PDF_WEEK_EVE_AVG' => 'Akşam Ortalama',
        'PDF_WEEK_DAY_AVG' => 'Günlük Ort.',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Tüm Değerler',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Mod, Algılanan Bilgiler (A.B.)',
        'PDF_WEEK_HINT_HEADER' => 'Algılanan Bilgiler (A.B.)',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_HIND_NOTES' => 'Not Simgesi',
        // 改造BP_APP_DEV-1103 2019.09.18 VinhHP ---->
        'PDF_WEEK_HINT_MODE' => 'Modu',
        // BP_APP_DEV-1103 2019.09.18 <----
        'PDF_WEEK_HINT_MODE_DES' => 'Afib modunda ölçüm yaparken görünür.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Modu',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Gece modunda ölçüm yaparken görünür.',
        // BP_APP_DEV-1271 2020.04.21 <----
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Ölçüm hatası',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Ölçüm sırasında Konumlandırma Göstergesinde,Bileklik',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'Kılavuzunda veya Vücut Hareketinde bir hata algılandığında',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'görünür.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Düzensiz nabız',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Bir ölçüm sırasında iki kez veya daha fazla düzensiz ritm tespit',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'edildi.',
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Ölçüm sırasında olası atriyal fibrilasyon (AFib) tespit edildiğinde',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'görüntülenir.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Egzersiz',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Egzersiz eksikliği',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Tuzu Azaltma',
        'PDF_WEEK_HINT_SALT' => 'Tuz',
        'PDF_WEEK_HINT_VEGETABLES' => 'Sebze',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Az sebze',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Alkol yok',
        'PDF_WEEK_HINT_ALCOHOL' => 'Alkol',
        'PDF_WEEK_HINT_SLEEP' => 'Uyku',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Yetersiz uyku',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Sigara yok',
        'PDF_WEEK_HINT_SMOKING' => 'Sigara',
        'PDF_WEEK_HINT_CONSULTATION' => 'Konsültasyon',
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Algılanan Bilgiler (A.B.)',
        // BP_APP_DEV-1059 2019.09.17 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        'PDF_WEEK_DATE' => 'Tarih',
        'PDF_WEEK_TIME' => 'Saat',
        // 改造BP_APP_DEV-1059 2019.09.05 TanND ---->
        'PDF_WEEK_DI' => 'A.B.',
        'PDF_WEEK_MED' => 'İlaç',
        // BP_APP_DEV-1059 2019.09.05 <----
        'PDF_WEEK_NOTES' => 'Notlar',
        'PDF_PAGE'  => 'Sayfa '
        // BP_APP_DEV-1059 2018.08.23 <----
    ],
    'PT' => [
        'title' => '[OMRON connect]Enviando seu livro de registros de pressão arterial',
        'body' =>
            "Prezado tpl_user,\n\n" .
            "Agradecemos por utilizar nossos serviços de tratamento da pressão arterial e o OMRON connect.\n" .
            "Estamos enviando em anexo o livro de registros de pressão arterial exportado.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            "Atenciosamente,\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  Todos os direitos reservados.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Este e-mail foi enviado de um endereço apenas para notificações e que não recebe e-mails. Não responda a esta mensagem.\n",

        // constants for pdf template send mail

        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        // 改造BP_APP_DEV-2889 2023.06.23 TrungNT ---->
        'PDF_TITLE' => 'Registro da Pressão Arterial (média da PA matinal e noturna)',
        // 改造BP_APP_DEV-2889 2023.06.23 <----
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_TITLE_SUB' => 'Média/semanal.',
        'PDF_DATE_OUTPUT' => 'n/j, Y H:i',
        'PDF_DATE_FORMAT' => 'd/m/Y',
        //'PDF_DATE_FORMAT' => 'n/j/Y',
        'PDF_MONTH_FORMAT' => 'n/j',
        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'en_EN',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        'PDF_WEEK_FORMAT' => 'n/j',
        'PDF_DAY_FORMAT' => 'M d D',
        'PDF_DATE_SEPARATE' => ' ~ ',
        'PDF_NAME' => 'Nome: ',
        'PDF_DATE' => 'Data',
        'PDF_DATES' => ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        'PDF_MONTH' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        'PDF_BP' => 'Pressão arterial',
        'PDF_TIME' => 'Hora',
        'PDF_SYS' => 'SYS',
        'PDF_DIA' => 'DIA',
        'PDF_PULSE' => 'Pulse',
        'PDF_CONSULTATION' => 'Consulta',
        'PDF_MEDICATION' => 'Medicamento',
        'PDF_NOTES' => 'Anotações',
        'PDF_NOTES2' => 'Anotações',
        'PDF_PERIOD' => 'Periodo',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_AVG' => 'Dias medidos/semana.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_AVG_PERIOD' => 'Média/período',
        'PDF_AVG_TEXT' => 'Méd.',
        'PDF_DATE_CREATE' => 'Criar PDF em: ',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE1' => 'Aos profissionais de saúde',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_FOOTER_LINE2' => '* This chart is plotted only with blood pressure values around waking time and bedtime according to hypertensive treatment guidelines.',
        'PDF_FOOTER_LINE2_SUB' => '&nbsp;&nbsp;&nbsp;Please use the data as a reference in your treatment plan.',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3' => 'Pressão arterial matinal e pulso',
        'PDF_FOOTER_LINE4' => 'Pressão arterial noturna e pulso',
        'PDF_FOOTER_LINE3_2' => 'Média das 2 primeiras leituras realizadas dentro de 10 minutos entre mor_start e mor_end da manhã.',
        'PDF_FOOTER_LINE4_2' => 'Média das 2 últimas leituras realizadas dentro de 10 minutos entre eve_start e eve_end.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => '',
        'PDF_FOOTER_LINE5' => 'Se o número de medições for menor do que três, a média será calculada com base no número de medições realizadas.',
        'PDF_TITLE_CSV' => 'Data da medição,Fuso horário,SYS,DIA,Pulse,Nome do modelo do dispositivo',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_LESSTHAN_TEXT' => 'Abaixo de',
        'PDF_YOUR_TARGET_SYS' => 'Sua meta de pressão SYS',
        'PDF_YOUR_TARGET_DIA' => 'Sua meta de pressão DIA',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_ACHIEVEMENT_LEVEL' => 'Nível de desempenho',
        'PDF_TARGET_ACHIEVED' => 'Meta alcançada',
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Igual ou ligeiramente ',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'acima',
        'PDF_HIGHER' => 'Acima',
        'PDF_MUCH_HIGHER' => 'Muito acima',
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Livro de registro da pressão arterial ( Média diária )',
        'PDF_DAY_AVG_FOOTER_1' => 'Média diária',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_DAY_AVG_FOOTER_2' => 'Média de todas as medições em um dia.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Livro de registro da pressão arterial ( Lista )',
        'PDF_WEEK_AVG_NAME' => 'Nome',
        'PDF_WEEK_AVG_HEADER' => 'Média semanal',
        'PDF_WEEK_PERIOD' => 'Periodo',
        'PDF_WEEK_MOR_AVG' => 'Média manhã',
        'PDF_WEEK_EVE_AVG' => 'Média noite',
        'PDF_WEEK_DAY_AVG' => 'Média diária',
        'PDF_WEEK_SYS' => 'SYS',
        'PDF_WEEK_DIA' => 'DIA',
        'PDF_WEEK_PULSE' => 'Pulse',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Todas as leituras',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Modo, Informações detectadas (I.D.)',
        'PDF_WEEK_HINT_HEADER' => 'Informações detectadas (I.D.)',
        'PDF_WEEK_HIND_NOTES' => 'Ícone de anotações',
        'PDF_WEEK_HINT_MODE' => 'Mode',
        'PDF_WEEK_HINT_MODE_DES' => 'É exibido ao medir no modo de Afib.',
        // 改造BP_APP_DEV-1271 2020.04.21 TranHV ---->
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Mode',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'É exibido ao medir no modo noturno.',
        // BP_APP_DEV-1271 2020.04.21 <----
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Erro de medição',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'É exibido ao detectar um erro no indicador de posicionamento,',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'na guia da braçadeira ou no movimento corporal',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_3' => 'durante a medição.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Batimentos irregularesa',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Foi detectado ritmo cardíaco irregular duas ou mais vezes ',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'durante uma medição.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.14 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Exibe quando uma possível fibrilação atrial (AFib) é detectada',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'durante a medição.',
        // 改造BP_APP_DEV-4599 2025.04.14 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Exercício',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Falta de exercício',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Redução de sal',
        'PDF_WEEK_HINT_SALT' => 'Sal',
        // 改造BP_APP_DEV-1059 2019.09.19 VinhHP ---->
        'PDF_WEEK_HINT_VEGETABLES' => 'Vegetais',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Poucos vegetais',
        // BP_APP_DEV-1059 2019.09.19 <----
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Sem álcool',
        'PDF_WEEK_HINT_ALCOHOL' => 'Álcool',
        'PDF_WEEK_HINT_SLEEP' => 'Sono',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Falta de sono',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Sem fumar',
        'PDF_WEEK_HINT_SMOKING' => 'Fumar',
        // 改造BP_APP_DEV-2960 2023.06.23 TrungNT ---->
        'PDF_WEEK_HINT_CONSULTATION' => 'Hospital',
        // 改造BP_APP_DEV-2960 2023.06.23 <----
        // 改造BP_APP_DEV-1059 2019.09.19 VinhHP ---->
        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Informações detectadas (I.D.)',
        // BP_APP_DEV-1059 2019.09.19 <----
        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        // 改造BP_APP_DEV-1059 2019.09.17 VinhHP ---->
        'PDF_WEEK_DATE' => 'Data',
        // BP_APP_DEV-1059 2019.09.17 <----
        'PDF_WEEK_TIME' => 'Hora',
        'PDF_WEEK_DI' => 'I.D.',
        'PDF_WEEK_MED' => 'Med.',
        'PDF_WEEK_NOTES' => 'Anotações',
        'PDF_PAGE'  => 'página '
    ],
    // BP_APP_DEV-1038 2019.05.30 <----
    // BP_APP_DEV-2009 2022.07.04 TrungNT ---->
    'VI' => [
        'title' => '[OMRON connect] Gửi Nhật ký huyết áp của bạn',
        'body' =>
            "Kính gửi tpl_user,\n\n" .
            "Cảm ơn bạn đã sử dụng dịch vụ OMRON connect và Quản lý huyết áp của chúng tôi.\n" .
            "Vui lòng tìm Sổ nhật ký Huyết áp đã xuất được đính kèm trong email này.\n" .
            "\n" .
            "tpl_filename\n" .
            "\n" .
            "Trân trọng,\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "OMRON connect\n" .
            "https://www.omronconnect.com/\n" .
            "\n" .
            "Bản quyền (c) 2016 OMRON HEALTHCARE Co., Ltd. Mọi quyền được bảo lưu.\n" .
            "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" .
            "*Thư email này được gửi từ một địa chỉ chỉ để thông báo, không thể chấp nhận email đến. Vui lòng không trả lời tin nhắn này.\n",

        // constants for pdf template send mail
        //'PDF_TITLE' => 'Morning & Night BP Logbook ( Period: ',
        'PDF_TITLE' => 'Sổ nhật ký huyết áp (Huyết áp trung bình buổi sáng và buổi tối)',
        'PDF_TITLE_SUB' => ' Trung bình / tuần.',
        'PDF_DATE_OUTPUT' => 'd/m/Y H:i',
        'PDF_DATE_FORMAT' => 'd/m/Y',

        // BP_APP_DEV-2191 2022-07-22 ThangNT ---->
        'PDF_MONTH_FORMAT' => 'j/n',
        // BP_APP_DEV-2191 2022-07-22 ThangNT ---->

        'PDF_ONLY_MONTH' => 'M',
        'PDF_LOCALE' => 'vi_VN',
        'PDF_ONLY_DAY' => 'j',
        'PDF_ONLY_DAY_OF_WEEK' => 'D',
        'PDF_WEEK_FORMAT' => 'M j',
        'PDF_DAY_FORMAT' => 'M d D',
        'PDF_DATE_SEPARATE' => ' - ',
        'PDF_NAME' => 'Tên: ',
        'PDF_DATE' => 'Ngày tháng',
        // 改造BP_APP_DEV-2192 202.07.22 TrungNT ---->
        'PDF_DATES' => ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'],
        'PDF_DATES_SHORTER' => ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        // 改造BP_APP_DEV-2192 202.07.22 <----
        'PDF_MONTH' => ['Thg 1','Thg 2','Thg 3','Thg 4','Thg 5','Thg 6','Thg 7','Thg 8','Thg 9','Thg 10','Thg 11','Thg 12'],
        'PDF_BP' => 'Huyết áp',
        'PDF_TIME' => 'Thời gian',
        'PDF_SYS' => 'HATT',
        'PDF_DIA' => 'HATTr',
        'PDF_PULSE' => 'Nhịp tim',

        // BP_APP_DEV-2191 2022-07-22 ThangNT ---->
        'PDF_CONSULTATION' => 'Tham vấn',
        // BP_APP_DEV-2191 2022-07-22 ThangNT <----

        'PDF_MEDICATION' => 'Thuốc',
        'PDF_NOTES' => 'Ghi chú',
        'PDF_NOTES2' => 'Ghi chú',
        'PDF_PERIOD' => 'Khoảng thời gian',
        'PDF_AVG' => 'Số ngày đo / tuần..',
        'PDF_AVG_PERIOD' => 'Trung bình / kỳ',
        'PDF_AVG_TEXT' => 'T.bình',
        'PDF_DATE_CREATE' => 'Thời gian tạo PDF: ',
        'PDF_FOOTER_LINE1' => 'Gửi các chuyên gia y tế',
        'PDF_FOOTER_LINE2' => '* Biểu đồ này chỉ được vẽ với các giá trị huyết áp ở khoảng thời gian thức dậy và trước khi đi ngủ theo hướng dẫn điều trị tăng huyết áp.',
        'PDF_FOOTER_LINE3' => 'Huyết áp và nhịp tim vào buổi sáng',
        'PDF_FOOTER_LINE4' => 'Huyết áp & nhịp tim buổi tối',
        // 改造BP_APP_DEV-2678 2023.06.01 TrungNT ---->
        'PDF_FOOTER_LINE3_2' => 'Giá trị trung bình của 2 lần đo đầu tiên được thực hiện trong vòng 10 phút trong khoảng thời gian từ 4:00 sáng đến 11:59 trưa',
        'PDF_FOOTER_LINE4_2' => 'Kết quả trung bình của 2 lần đo cuối cùng được thực hiện trong vòng 10 phút từ 7:00 tối đến 1:59 sáng.',
        // 改造BP_APP_DEV-2678 2023.06.01 <----
        'PDF_NEXT_DAY'      => 'ngày kế tiếp',
        'PDF_FOOTER_LINE5' => 'Nếu số lần đo nhỏ hơn ba, giá trị trung bình sẽ được tính dựa trên số lần đo.',
        'PDF_TITLE_CSV' => 'Ngày đo huyết áp,Múi giờ,Huyết áp tâm thu,Huyết áp tâm trương,Nhịp tim,Tên Model thiết bị',
        'PDF_LESSTHAN_TEXT' => 'Ít hơn',
        'PDF_YOUR_TARGET_SYS' => 'Mục tiêu HATT',
        'PDF_YOUR_TARGET_DIA' => 'Mục tiêu HATTr',
        'PDF_ACHIEVEMENT_LEVEL' => 'Mức độ thành tích',
        'PDF_TARGET_ACHIEVED' => 'Mục tiêu đạt được',

        // BP_APP_DEV-2191 2022-07-22 ThangNT ---->
        'PDF_EQUAL_OR_SLIGHTLY_1' => 'Bằng hoặc',
        'PDF_EQUAL_OR_SLIGHTLY_2' => 'cao hơn một chút',
        // BP_APP_DEV-2191 2022-07-22 ThangNT <----

        'PDF_HIGHER' => 'Cao hơn',
        'PDF_MUCH_HIGHER' => 'Cao hơn nhiều',
        // TEXT DAY AVERAGE
        'PDF_DAY_AVG_TITLE' => 'Sổ nhật ký huyết áp (Trung bình hàng ngày)',
        'PDF_DAY_AVG_FOOTER_1' => 'Trung bình hàng ngày',
        'PDF_DAY_AVG_FOOTER_2' => 'Kết quả trung bình của tất cả các lần đo trong một ngày.',
        //WEEK AVG
        'PDF_WEEK_AVG_TITLE' => 'Sổ nhật ký huyết áp (Danh sách)',
        'PDF_WEEK_AVG_NAME' => 'Tên',
        'PDF_WEEK_AVG_HEADER' => 'Trung bình hàng tuần',
        'PDF_WEEK_PERIOD' => 'Khoảng thời gian',
        'PDF_WEEK_MOR_AVG' => 'Trung bình buổi sáng',
        'PDF_WEEK_EVE_AVG' => 'Trung bình buổi tối',
        'PDF_WEEK_DAY_AVG' => 'Trung bình hàng ngày',
        'PDF_WEEK_SYS' => 'HATT',
        'PDF_WEEK_DIA' => 'HATTr',
        'PDF_WEEK_PULSE' => 'Nhịp tim',
        'PDF_WEEK_ALL_RECORD_HEADER' => 'Tất cả các kết quả đo',
        'PDF_WEEK_HINT_MODE_HEADER' => 'Cách thức, Thông tin được phát hiện (D.I.)',
        'PDF_WEEK_HINT_HEADER' => 'Thông tin được phát hiện (D.I.)',
        'PDF_WEEK_HIND_NOTES' => 'Biểu tượng ghi nhớ',
        'PDF_WEEK_HINT_MODE' => 'Chế độ',
        'PDF_WEEK_HINT_MODE_DES' => 'Xuất hiện khi đo ở chế độ Afib.',
        'PDF_WEEK_HINT_MODE_NIGHT' => 'Chế độ',
        'PDF_WEEK_HINT_MODE_NIGHT_DES' => 'Xuất hiện khi đo ở chế độ ban đêm.',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR' => 'Lỗi đo',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1' => 'Xuất hiện khi phát hiện lỗi trong Chỉ báo Định vị, Hướng dẫn ',
        'PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2' => 'Quấn vòng bít, hoặc Chuyển động của Cơ thể trong quá trình đo.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT' => 'Nhịp tim không đều',
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_1' => 'Một nhịp bất thường đã được phát hiện hai hoặc nhiều lần ',
        'PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC_2' => 'trong quá trình đo.',
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        'PDF_WEEK_HINT_AFIB' => 'Afib',
        // 改造BP_APP_DEV-4599 2025.04.21 LoiNV ---->
        'PDF_WEEK_HINT_AFIB_DESC_1' => 'Xuất hiện khi phát hiện rung nhĩ có thể xảy ra (AFib) trong quá trình',
        'PDF_WEEK_HINT_AFIB_DESC_2' => 'đo.',
        // 改造BP_APP_DEV-4599 2025.04.21 <----
        'PDF_WEEK_HINT_EXCERCISE' => 'Luyện tập',
        'PDF_WEEK_HINT_LACK_OF_EXCERCISE' => 'Thiếu luyện tập',
        'PDF_WEEK_HINT_REDUCING_SALT' => 'Giảm muối',
        'PDF_WEEK_HINT_SALT' => 'Muối',
        'PDF_WEEK_HINT_VEGETABLES' => 'Rau',
        'PDF_WEEK_HINT_LESS_VEGETABLES' => 'Ít rau',
        'PDF_WEEK_HINT_NO_ALCOHOL' => 'Không rượu',
        'PDF_WEEK_HINT_ALCOHOL' => 'Rượu',
        'PDF_WEEK_HINT_SLEEP' => 'Ngủ',
        'PDF_WEEK_HINT_LACK_OF_SLEEP' => 'Thiếu ngủ',
        'PDF_WEEK_HINT_NOT_SMOKING' => 'Không hút thuốc',
        'PDF_WEEK_HINT_SMOKING' => 'Hút thuốc',

        // BP_APP_DEV-2191 2022-07-22 ThangNT ---->
        'PDF_WEEK_HINT_CONSULTATION' => 'Tham vấn',
        // BP_APP_DEV-2191 2022-07-22 ThangNT <----

        'PDF_WEEK_HINT_DETECT_INFOMATION' => 'Thông tin được phát hiện (D.I.)',

        // 改造BP_APP_DEV-4288 2025.02.10 LoiNV ---->
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL' => 'Pulse interval irregularity',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_1' => '”Pulse interval irregularity” is displayed when the pulse is',
        'PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC_2' => 'always irregular during measurement.',
        // 改造BP_APP_DEV-4288 2025.02.10 <----
        
        'PDF_WEEK_DATE' => 'Ngày tháng',
        'PDF_WEEK_TIME' => 'Thời gian',
        'PDF_WEEK_DI' => 'D.I.',
        'PDF_WEEK_MED' => 'Thuốc',
        'PDF_WEEK_NOTES' => 'Ghi chú',
        'PDF_PAGE'  => 'trang ',
        'PDF_WEEK_MODE_SHORT' => 'C.độ',
        'PDF_WEEK_PULSE_SHORT' => 'N.tim',
    ],
    // BP_APP_DEV-2009 2022.07.04 <----
    'regular_report' => [
        'JA' => [
            'title_mail' => '[OMRON connect]血圧お知らせ定期便',
            'wellcome' => '「血圧お知らせ定期便」より、tpl_nickname（測定者）様の週間レポートをお送りしております。',
            'list_dates' => ['月', '火', '水', '木', '金', '土', '日'],
            'format_date' => 'm月d日',
            'ms_full' => '今週は、毎日測定されていました。素晴らしい！',
            'ms_not_full' => '今週は、１週間のうちXX日測定されていました。',
            'ms_no_have' => '今週は、測定されていませんでした…。',
            'footer' => "\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "OMRON connect\n" .
                "https://www.omronconnect.com/\n" .
                "\n" .
                "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                // 改造BP_APP_DEV-1039 2019.05.16 TranHV ---->
                "※本メールは送信専用アドレスから自動送信されています。返信を受信できないため、本メールに対するご返信はご遠慮ください。\n\n" .
                "※配信停止される場合は「<a href='%s'>配信停止手続き</a>」の設定をおこなってください。\n配信停止手続き後も処理の都合により、数回配信されることがございます。何卒ご了承ください。" . "\n",
                // BP_APP_DEV-1039 2019.05.16 <----
            'bp_day' => '測定日',
            'bp_measure' => '朝晩血圧と目標達成度',
            'bp_count' => '測定回数',
            'confirm_success_content000' => '定期便の受け取りを承認していただき',
            'confirm_success_content001' => 'ありがとうございました',
            'confirm_success_content002' => '血圧や体重などのオムロンの健康機器で測定したバイタルデータをスマートフォンに転送し、簡単に管理できるアプリケーションです。',
            'confirm_success_content003' => '他社アプリやサービスとデータ連携できます。(iPhone・Android対応 / 無料)',
            'confirm_success_content004' => 'OMRON connect について詳しく知る',
            'confirm_success_content005' => 'あなたの血圧管理をもっと簡単に。',
            'confirm_success_content006' => '<b>OMRON connect</b> アプリ対応',
            'confirm_success_content007' => 'ホーム画面',
            'confirm_success_content008' => '最新の血圧を確認',
            'confirm_success_content009' => 'グラフ画面',
            'confirm_success_content010' => '血圧の変化を確認',
            'confirm_failed_content000' => '承認エラーが発生しました',
            'confirm_failed_content001' => 'クリックされた承認リクエストのURLは期限切れの可能性があります。',
            'confirm_failed_content002' => '最新の承認リクエストメールが届いているかご確認ください。',
            'teikibin_unsub_content000' => '血圧お知らせ定期便の',
            'teikibin_unsub_content001' => '配信停止手続き',
            'teikibin_unsub_content002' => '<span class="nick">tpl_nickname</span>「血圧お知らせ定期便」配信を停止してもよろしいですか？',
            'teikibin_unsub_content003' => '配信停止をすると、再開する場合に測定者様より',
            'teikibin_unsub_content004' => '再度申請をいただく必要があります。',
            'teikibin_unsub_content005' => '問題なければ、以下の',
            'teikibin_unsub_content006' => '配信を停止する',
            'teikibin_unsub_content007' => 'ボタンをクリックしてください。',
            'teikibin_unsub_content008' => '配信を停止する',
            'teikibin_unsub_success_content000' => '配信停止の手続き完了',
            'teikibin_unsub_success_content001' => '「血圧お知らせ定期便」の配信停止処理が',
            'teikibin_unsub_success_content002' => '完了しました。',
            'teikibin_unsub_success_content004' => '※配信停止手続き後も処理の都合により、',
            'teikibin_unsub_success_content005' => '数回配信されることがございます。',
            'teikibin_unsub_success_content006' => '何卒ご了承ください。',
            'teikibin_unsub_fail_content000' => 'エラーが発生しました',
            'teikibin_unsub_fail_content001' => '血圧お知らせ定期便の配信停止の手続きを',
            'teikibin_unsub_fail_content002' => 'もう一度最初からやり直してください。',
            'teikibin_unsub_fail_content003' => '対処方法：',
            'teikibin_unsub_fail_content004' => '本ページを閉じて、血圧お知らせ定期便のメールの「配信停止」のリンクを再度クリックし、',
            'teikibin_unsub_fail_content005' => '配信停止手続きを開始してください。',
            'relogin_regular_report_message_1' => '【重要なお知らせ】',
            'relogin_regular_report_message_2' =>
                '2021 年 11 月 27 日（土）に、クラウドサービスのシステ' .
                'ム移行を実施いたします。システム移行後は自動的にサイ' .
                'ンアウトされるため、',
            'relogin_regular_report_message_3' => '測定者様ご自身で OMRON connect アプリよりサインインをしていただく必要があります。',
            'relogin_regular_report_message_4' => '「血圧お知らせ定期便」ご利用者様への影響',
            'relogin_regular_report_message_5' =>
                '測定者様がサインインを完了していない場合、定期便メー' .
                'ルの配信は停止されます。必ずシステム移行後にアプリの' .
                '案内に従ってご対応いただきますようお願いいたします。'
        ],
        'ZH' => [
            'title_mail' => '[OMRON connect]每週血壓報告',
            'wellcome' => 'tpl_nickname (測量者)向您寄送了本週的「每週血壓報告」。',
            'list_dates' => ['一', '二', '三', '四', '五', '六', '日'],
            'format_date' => 'm月d日',
            'ms_full' => '本週每天都有測量。很棒!',
            'ms_not_full' => '本週內有XX天做測量。',
            'ms_no_have' => '本週沒有測量。',
            'footer' => "\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "OMRON connect\n" .
                "https://www.omronconnect.com/\n" .
                "\n" .
                "Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                // 改造BP_APP_DEV-1039 2019.05.16 TranHV ---->
                "※本郵件由寄送專用電子郵件信箱自動寄送。請勿回覆本信件。\n\n" .
                "※停止寄送時請從「<a href='%s'>停止寄送程序</a>」上做設定。\n設定停止寄送後需要處理時間，可能還會寄出幾次信件。" . "\n",
                // BP_APP_DEV-1039 2019.05.16 <----
            'bp_day' => '測量日',
            'bp_measure' => '早晚血壓及目標達成度',
            'bp_count' => '測量次數',
            'confirm_success_content000' => '感謝您確認接受每週血壓報告',
            'confirm_success_content001' => '',
            'confirm_success_content002' => '可將歐姆龍測量血壓或體重健康機器的數據傳送到智慧型手機，簡單管理的應用程式。',
            'confirm_success_content003' => '可與其他公司應用程式或服務做資料連結。(iPhone‧Android對應 /免費)',
            'confirm_success_content004' => '詳細了解OMRON connect',
            'confirm_success_content005' => '血壓管理更加簡單。',
            'confirm_success_content006' => '對應<b>OMRON connect</b> 應用程式',
            'confirm_success_content007' => '首頁畫面',
            'confirm_success_content008' => '確認最新血壓',
            'confirm_success_content009' => '圖表畫面',
            'confirm_success_content010' => '確認血壓變化',
            'confirm_failed_content000' => '發生驗證錯誤',
            'confirm_failed_content001' => '點擊的驗證URL有可能已經超過期限。',
            'confirm_failed_content002' => '請確認是否有最新的驗證信件寄達。',
            'teikibin_unsub_content000' => '每週血壓報告寄送停止通知',
            'teikibin_unsub_content001' => '',
            'teikibin_unsub_content002' => '是否停止寄送<span class="nick">tpl_nickname</span>的「每週血壓報告」？',
            'teikibin_unsub_content003' => '一旦停止寄送後、若要重新啟用需要由測量者重新申請。',
            'teikibin_unsub_content004' => '',
            'teikibin_unsub_content005' => '若沒有問題，請點擊以下',
            'teikibin_unsub_content006' => '停止寄送',
            'teikibin_unsub_content007' => '的按鈕。',
            'teikibin_unsub_content008' => '停止寄送',
            'teikibin_unsub_success_content000' => '已完成停止寄送',
            'teikibin_unsub_success_content001' => '已完成停止寄送「每週血壓報告」。',
            'teikibin_unsub_success_content002' => '',
            'teikibin_unsub_success_content004' => '※停止發送後因需要時間做資料處理、',
            'teikibin_unsub_success_content005' => '可能還會收到收到信件。',
            'teikibin_unsub_success_content006' => '',
            'teikibin_unsub_fail_content000' => '發生錯誤',
            'teikibin_unsub_fail_content001' => '請重新進行每週血壓報告停止發送程序。',
            'teikibin_unsub_fail_content002' => '',
            'teikibin_unsub_fail_content003' => '對應方式:',
            'teikibin_unsub_fail_content004' => '關閉本頁後，請點擊每週血壓報告的「停止發送」連結，重新進行停止發送程序。',
            'teikibin_unsub_fail_content005' => '',
            'relogin_regular_report_message_1' => '【重要通知】',
            'relogin_regular_report_message_2' =>
                '2021年11月27日（六）將會進行雲端服務系統轉移作業。' .
                '系統轉移後會自動登出，屆時將需要用戶重新進行登入。',
            'relogin_regular_report_message_3' => '',
            'relogin_regular_report_message_4' => '對「每週血壓報告」使用者的影響',
            'relogin_regular_report_message_5' =>
                '用戶重新登入前，「每週血壓報告」自動寄送功能會停止。' .
                '請務必於系統轉移完成後，遵照APP指示完成重新登入。'
        ]
    ],

);