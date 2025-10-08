import { confirmFailedCSS } from '/opt/public/css/confirm_failed';
import * as fs from 'fs';
import { regularReport } from '/opt/constants/regular-reports';
import { LANGUAGE } from '/opt/constants/common';
import { LoggerService } from '/opt/services/logger.service';
// Helper function for handling newlines
function nl2br(str: string): string {
  return str.replace(/\\n/g, '<br>').replace(/\n/g, '<br>');
}

/**
 * Loads and converts the logo image to base64 format
 * @returns Base64 encoded logo image
 */
export function loadLogoImage(): string {
  let logoImage = '';
  
  try {
    // Read logo
    const logoPath = '/opt/public/imgs/confirm_email_success/logo.png';
    if (fs.existsSync(logoPath)) {
      const imageBuffer = fs.readFileSync(logoPath);
      logoImage = `data:image/png;base64,${imageBuffer.toString('base64')}`;
      LoggerService.defaultWriteLog('Logo image loaded');
    }
  } catch (error) {
    LoggerService.defaultWriteLog(`Error reading logo image: ${error}`);
  }

  return logoImage;
}

/**
 * Creates and returns the complete HTML for the failed verification page
 * @param lang Language code (default: 'JA')
 * @returns Complete HTML string with embedded logo image
 */
export function createConfirmFailedPage(lang: string = 'JA'): string {
  const logoImage = loadLogoImage();
  return generateConfirmFailedHtml(lang, logoImage);
}

/**
 * Generates HTML for the email confirmation failed page
 * @param lang Language code (default: 'JA')
 * @param logoImage Base64 encoded logo image
 * @returns HTML string for the failed verification page
 */
export function generateConfirmFailedHtml(
  lang: string = 'JA',
  logoImage: string = ''
): string {
  // Default to Japanese if language not provided
  const language = lang || LANGUAGE.DEFAULT;
  
  // Use the messages from regular-reports.ts
  const messages = regularReport[language] || regularReport.JA;

  return `<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>${confirmFailedCSS}</style>
</head>
<body>
<div id="main_load_data">
    <div id="head">
        <div class="lay"><h1><img src="${process.env.BP_STATIC_URL}public/imgs/confirm_email/logo.png" alt="OMRON All for Healthcare"></h1></div>
    </div>
    <div id="mv">
        <h2>${nl2br(messages.confirm_failed_content000)}</h2>
        <p>
        ${nl2br(messages.confirm_failed_content001)}<br><br>
        ${nl2br(messages.confirm_failed_content002)}
        </p>
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
