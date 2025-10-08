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
 * Creates and returns the HTML for the unsubscribe success page
 * @param lang Language code (default: 'JA')
 * @returns HTML string
 */
export function createUnsubscribeSuccessPage(lang: string = 'JA'): string {
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
    <title>かんたん血圧日記｜血圧お知らせ定期便-配信停止完了</title>
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
                <h2>${nl2br(messages.teikibin_unsub_success_content000 || '')}</h2>
        </div>
        <div id="main">
                
                <div id="lead">
                    
                    <p>
                    ${nl2br(messages.teikibin_unsub_success_content001 || '')}<br>${nl2br(messages.teikibin_unsub_success_content002 || '')}<br>
                        <br>
                    </p>
                    
                    <p class="footnote">
                    ${nl2br(messages.teikibin_unsub_success_content004 || '')}<br>${nl2br(messages.teikibin_unsub_success_content005 || '')}<br>
                    ${nl2br(messages.teikibin_unsub_success_content006 || '')}</p>
                    
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