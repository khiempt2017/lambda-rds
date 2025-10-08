import { regularReport } from '/opt/constants/regular-reports';
import { LANGUAGE } from '/opt/constants/common';

/**
 * Helper function for handling newlines
 * @param str String to process
 * @returns String with newlines converted to <br>
 */
function nl2br(str: string): string {
  return str.replace(/\n/g, '<br>');
}

/**
 * Helper function to replace template placeholder
 * @param template String containing template placeholder
 * @param nick Nickname to replace placeholder with
 * @param lang Language code
 * @returns String with placeholder replaced
 */
function replaceTplNickname(template: string, nick: string, lang: string): string {
  if (nick && lang === 'JA') {
    return template.replace('tpl_nickname', `${nick}様の`);
  } else {
    return template.replace('tpl_nickname', nick);
  }
}

/**
 * Creates and returns the HTML for the unsubscribe request page
 * @param lang Language code (default: 'JA')
 * @param authKey Authentication key
 * @param nick User nickname
 * @param queryData Encrypted query data
 * @returns HTML string
 */
export function createUnsubscribeRequestPage(
  lang: string = 'JA',
  authKey: string = '',
  nick: string = '',
  queryData: string = ''
): string {
  // Default to Japanese if language not provided
  const language = lang || LANGUAGE.DEFAULT;
  
  // Use the messages from regular-reports.ts
  const messages = regularReport[language] || regularReport.JA;
  
  // CloudFront base URL
  const cloudFrontUrl = process.env.BP_STATIC_URL;

  // Generate the HTML that matches the PHP template exactly
  return `<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>かんたん血圧日記｜血圧お知らせ定期便-配信停止の手続き</title>
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="${cloudFrontUrl}public/css/unsubscribe/teikibin_unsubscribe_style.css">
</head>
<body>
    <div id="main_load_data">

        <div id="head">
            <div class="lay"><h1><img src="${cloudFrontUrl}public/imgs/setting/logo.png" alt="OMRON All for Healthcare"></h1></div>
        </div>
        <div id="mv">
                <h2>${nl2br(messages.teikibin_unsub_content000 || '')}<br>${nl2br(messages.teikibin_unsub_content001 || '')}</h2>
        </div>
        <div id="main">
                <div id="lead">
                    <p>
                    ${replaceTplNickname(nl2br(messages.teikibin_unsub_content002 || ''), nick, language)}
                    <br><br>
                    ${nl2br(messages.teikibin_unsub_content003 || '')}<br>${nl2br(messages.teikibin_unsub_content004 || '')}<br>
                    ${nl2br(messages.teikibin_unsub_content005 || '')}
                    "<strong>${nl2br(messages.teikibin_unsub_content006 || '')}</strong>"
                    ${nl2br(messages.teikibin_unsub_content007 || '')}<br>
                    </p>
                    <div id="btn">
                        <a href="${process.env.APP_URL}/unsubscribe?${queryData}" class="cp_btn">${nl2br(messages.teikibin_unsub_content008 || '')}</a>
                    </div>
                </div>

        </div>
        <div id="foot_link">
            <ul>
                <li><a href="https://www.omronconnect.com/privacy" target="_blank">Privacy Policy</a></li>
                <li><a href="https://www.omronconnect.com/eula">Terms of Use</a></li>
                <li><a href="https://www.omronconnect.com">OMRON connect</a></li>
            </ul>
        </div>
        <div id="foot"><p id="copyright">&copy; OMRON HEALTHCARE Co., Ltd. 2019. All Rights Reserved.</p></div>
    </div>
</body>
</html>`;
} 