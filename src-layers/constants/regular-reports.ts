export const RISK_BORDER_COLORS = {
  '#ecedf0': '#fafafa',
  '#005F9E': '#c4e0f4',
  '#D1406D': '#f6d1df',
  '#9b245a': '#e5c8d5',
  '#4f2437': '#d2c8cc'
} as const;

export const regularReport = {
  JA: {
    title_mail: '[OMRON connect]血圧お知らせ定期便',
    wellcome: '「血圧お知らせ定期便」より、tpl_nickname（測定者）様の週間レポートをお送りしております。',
    list_dates: ['月', '火', '水', '木', '金', '土', '日'],
    format_date: 'MM月DD日',
    ms_full: '今週は、毎日測定されていました。素晴らしい！',
    ms_not_full: '今週は、１週間のうちXX日測定されていました。',
    ms_no_have: '今週は、測定されていませんでした…。',
    footer: `
━━━━━━━━━━━━━━━━━━
OMRON connect
https://www.omronconnect.com/

Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.
━━━━━━━━━━━━━━━━━━
※本メールは送信専用アドレスから自動送信されています。返信を受信できないため、本メールに対するご返信はご遠慮ください。

※配信停止される場合は「<a href='%s'>配信停止手続き</a>」の設定をおこなってください。
配信停止手続き後も処理の都合により、数回配信されることがございます。何卒ご了承ください。`,
    bp_day: '測定日',
    bp_measure: '朝晩血圧と目標達成度',
    bp_count: '測定回数',
    confirm_success_content000: '定期便の受け取りを承認していただき',
    confirm_success_content001: 'ありがとうございました',
    confirm_success_content002: '血圧や体重などのオムロンの健康機器で測定したバイタルデータをスマートフォンに転送し、簡単に管理できるアプリケーションです。',
    confirm_success_content003: '他社アプリやサービスとデータ連携できます。(iPhone・Android対応 / 無料)',
    confirm_success_content004: 'OMRON connect について詳しく知る',
    confirm_success_content005: 'あなたの血圧管理をもっと簡単に。',
    confirm_success_content006: '<b>OMRON connect</b> アプリ対応',
    confirm_success_content007: 'ホーム画面',
    confirm_success_content008: '最新の血圧を確認',
    confirm_success_content009: 'グラフ画面',
    confirm_success_content010: '血圧の変化を確認',
    confirm_failed_content000: '承認エラーが発生しました',
    confirm_failed_content001: 'クリックされた承認リクエストのURLは期限切れの可能性があります。',
    confirm_failed_content002: '最新の承認リクエストメールが届いているかご確認ください。',
    teikibin_unsub_content000: '血圧お知らせ定期便の',
    teikibin_unsub_content001: '配信停止手続き',
    teikibin_unsub_content002: '<span class="nick">tpl_nickname</span>「血圧お知らせ定期便」配信を停止してもよろしいですか？',
    teikibin_unsub_content003: '配信停止をすると、再開する場合に測定者様より',
    teikibin_unsub_content004: '再度申請をいただく必要があります。',
    teikibin_unsub_content005: '問題なければ、以下の',
    teikibin_unsub_content006: '配信を停止する',
    teikibin_unsub_content007: 'ボタンをクリックしてください。',
    teikibin_unsub_content008: '配信を停止する',
    teikibin_unsub_success_content000: '配信停止の手続き完了',
    teikibin_unsub_success_content001: '「血圧お知らせ定期便」の配信停止処理が',
    teikibin_unsub_success_content002: '完了しました。',
    teikibin_unsub_success_content004: '※配信停止手続き後も処理の都合により、',
    teikibin_unsub_success_content005: '数回配信されることがございます。',
    teikibin_unsub_success_content006: '何卒ご了承ください。',
    teikibin_unsub_fail_content000: 'エラーが発生しました',
    teikibin_unsub_fail_content001: '血圧お知らせ定期便の配信停止の手続きを',
    teikibin_unsub_fail_content002: 'もう一度最初からやり直してください。',
    teikibin_unsub_fail_content003: '対処方法：',
    teikibin_unsub_fail_content004: '本ページを閉じて、血圧お知らせ定期便のメールの「配信停止」のリンクを再度クリックし、',
    teikibin_unsub_fail_content005: '配信停止手続きを開始してください。',
    relogin_regular_report_message_1: '【重要なお知らせ】',
    relogin_regular_report_message_2: '2021 年 11 月 27 日（土）に、クラウドサービスのシステム移行を実施いたします。システム移行後は自動的にサインアウトされるため、',
    relogin_regular_report_message_3: '測定者様ご自身で OMRON connect アプリよりサインインをしていただく必要があります。',
    relogin_regular_report_message_4: '「血圧お知らせ定期便」ご利用者様への影響',
    relogin_regular_report_message_5: '測定者様がサインインを完了していない場合、定期便メールの配信は停止されます。必ずシステム移行後にアプリの案内に従ってご対応いただきますようお願いいたします。'
  },
  ZH: {
    title_mail: '[OMRON connect]每週血壓報告',
    wellcome: 'tpl_nickname (測量者)向您寄送了本週的「每週血壓報告」。',
    list_dates: ['一', '二', '三', '四', '五', '六', '日'],
    format_date: 'MM月DD日',
    ms_full: '本週每天都有測量。很棒!',
    ms_not_full: '本週內有XX天做測量。',
    ms_no_have: '本週沒有測量。',
    footer: `
━━━━━━━━━━━━━━━━━━
OMRON connect
https://www.omronconnect.com/

Copyright (c) 2016 OMRON HEALTHCARE Co., Ltd.  All Rights Reserved.
━━━━━━━━━━━━━━━━━━
※本郵件由寄送專用電子郵件信箱自動寄送。請勿回覆本信件。

※停止寄送時請從「<a href='%s'>停止寄送程序</a>」上做設定。
設定停止寄送後需要處理時間，可能還會寄出幾次信件。`,
    bp_day: '測量日',
    bp_measure: '早晚血壓及目標達成度',
    bp_count: '測量次數',
    confirm_success_content000: '感謝您確認接受每週血壓報告',
    confirm_success_content001: '',
    confirm_success_content002: '可將歐姆龍測量血壓或體重健康機器的數據傳送到智慧型手機，簡單管理的應用程式。',
    confirm_success_content003: '可與其他公司應用程式或服務做資料連結。(iPhone‧Android對應 /免費)',
    confirm_success_content004: '詳細了解OMRON connect',
    confirm_success_content005: '血壓管理更加簡單。',
    confirm_success_content006: '對應<b>OMRON connect</b> 應用程式',
    confirm_success_content007: '首頁畫面',
    confirm_success_content008: '確認最新血壓',
    confirm_success_content009: '圖表畫面',
    confirm_success_content010: '確認血壓變化',
    confirm_failed_content000: '發生驗證錯誤',
    confirm_failed_content001: '點擊的驗證URL有可能已經超過期限。',
    confirm_failed_content002: '請確認是否有最新的驗證信件寄達。',
    teikibin_unsub_content000: '每週血壓報告寄送停止通知',
    teikibin_unsub_content001: '',
    teikibin_unsub_content002: '是否停止寄送<span class="nick">tpl_nickname</span>的「每週血壓報告」？',
    teikibin_unsub_content003: '一旦停止寄送後、若要重新啟用需要由測量者重新申請。',
    teikibin_unsub_content004: '',
    teikibin_unsub_content005: '若沒有問題，請點擊以下',
    teikibin_unsub_content006: '停止寄送',
    teikibin_unsub_content007: '的按鈕。',
    teikibin_unsub_content008: '停止寄送',
    teikibin_unsub_success_content000: '已完成停止寄送',
    teikibin_unsub_success_content001: '已完成停止寄送「每週血壓報告」。',  
    teikibin_unsub_success_content002: '',
    teikibin_unsub_success_content004: '※停止發送後因需要時間做資料處理、',
    teikibin_unsub_success_content005: '可能還會收到收到信件。',
    teikibin_unsub_success_content006: '',
    teikibin_unsub_fail_content000: '發生錯誤',
    teikibin_unsub_fail_content001: '請重新進行每週血壓報告停止發送程序。',
    teikibin_unsub_fail_content002: '',
    teikibin_unsub_fail_content003: '對應方式:',
    teikibin_unsub_fail_content004: '關閉本頁後，請點擊每週血壓報告的「停止發送」連結，重新進行停止發送程序。',
    teikibin_unsub_fail_content005: '',
    relogin_regular_report_message_1: '【重要通知】',
    relogin_regular_report_message_2: '2021年11月27日（六）將會進行雲端服務系統轉移作業。系統轉移後會自動登出，屆時將需要用戶重新進行登入。',
    relogin_regular_report_message_3: '',
    relogin_regular_report_message_4: '對「每週血壓報告」使用者的影響',
    relogin_regular_report_message_5: '用戶重新登入前，「每週血壓報告」自動寄送功能會停止。請務必於系統轉移完成後，遵照APP指示完成重新登入。'
  }
};
