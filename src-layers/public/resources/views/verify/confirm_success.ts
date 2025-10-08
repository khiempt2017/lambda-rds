import { confirmSuccessCSS } from '/opt/public/css/confirm_success';
import * as fs from 'fs';
import { regularReport } from '/opt/constants/regular-reports';
import { LANGUAGE, PUBLIC_FOLDER } from '/opt/constants/common';
import { LoggerService } from '/opt/services';
// Helper function for handling newlines
function nl2br(str: string): string {
  return str.replace(/\\n/g, '<br>').replace(/\n/g, '<br>');
}

/**
 * Loads and converts images to base64 format
 * @returns Object containing base64 encoded images
 */
export function loadImages(): {
  logo: string;
  mv: string;
  main: string;
  sp1: string;
  sp2: string;
  sp3: string;
  sp4: string;
} {
  let logoImage = '';
  let mvImage = '';
  let mainImage = '';
  let spImage1 = '';
  let spImage2 = '';
  let spImage3 = '';
  let spImage4 = '';
  
  try {
    // Read logo
    const logoPath = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'logo.png';
    if (fs.existsSync(logoPath)) {
      const imageBuffer = fs.readFileSync(logoPath);
      logoImage = `data:image/png;base64,${imageBuffer.toString('base64')}`;
    }
    
    // Read mv image
    const mvPath = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'mv.png';
    if (fs.existsSync(mvPath)) {
      const imageBuffer = fs.readFileSync(mvPath);
      mvImage = `data:image/png;base64,${imageBuffer.toString('base64')}`;
    }
    
    // Read main image
    const mainPath = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'main.jpg';
    if (fs.existsSync(mainPath)) {
      const imageBuffer = fs.readFileSync(mainPath);
      mainImage = `data:image/jpeg;base64,${imageBuffer.toString('base64')}`;
    }
    
    // Read smartphone images
    const spPath1 = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'img_sp-1.jpg';
    if (fs.existsSync(spPath1)) {
      const imageBuffer = fs.readFileSync(spPath1);
      spImage1 = `data:image/jpeg;base64,${imageBuffer.toString('base64')}`;
    }
    
    const spPath2 = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'img_sp-2.jpg';
    if (fs.existsSync(spPath2)) {
      const imageBuffer = fs.readFileSync(spPath2);
      spImage2 = `data:image/jpeg;base64,${imageBuffer.toString('base64')}`;
    }
    
    const spPath3 = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'img_sp-3.jpg';
    if (fs.existsSync(spPath3)) {
      const imageBuffer = fs.readFileSync(spPath3);
      spImage3 = `data:image/jpeg;base64,${imageBuffer.toString('base64')}`;
    }
    
    const spPath4 = PUBLIC_FOLDER.IMAGES_CONFIRM_EMAIL_SUCCESS + 'img_sp-4.jpg';
    if (fs.existsSync(spPath4)) {
      const imageBuffer = fs.readFileSync(spPath4);
      spImage4 = `data:image/jpeg;base64,${imageBuffer.toString('base64')}`;
    }
  } catch (error: any) {
    LoggerService.logError('Error reading images: ' + error.message);
  }

  return {
    logo: logoImage,
    mv: mvImage,
    main: mainImage,
    sp1: spImage1,
    sp2: spImage2,
    sp3: spImage3,
    sp4: spImage4
  };
}

/**
 * Creates and returns the complete HTML for the success page with images and proper language
 * @param lang Language code (default: 'JA')
 * @returns Complete HTML string with embedded images
 */
export function createConfirmSuccessPage(lang: string = 'JA'): string {
  const imageData = loadImages();
  return generateConfirmSuccessHtml(lang, imageData);
}

/**
 * Generates HTML for the email confirmation success page
 * @param lang Language code (default: 'JA')
 * @param imageData Object containing base64 encoded images
 * @returns HTML string for the success page
 */
export function generateConfirmSuccessHtml(
  lang: string = 'JA',
  imageData: {
    logo?: string;
    mv?: string;
    main?: string;
    sp1?: string;
    sp2?: string;
    sp3?: string;
    sp4?: string;
  } = {}
): string {
  // Default to Japanese if language not provided
  const language = lang || LANGUAGE.DEFAULT;
  
  // Use the messages from regular-reports.ts
  const messages = regularReport[language] || regularReport.JA;
  const imagePath = "public/imgs/confirm_email/";
  return `<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>${confirmSuccessCSS}</style>
</head>

<body>
<div id="main_load_data">
    <div id="head">
        <div class="lay"><h1><img src="${process.env.BP_STATIC_URL}${imagePath}logo.png" alt="OMRON All for Healthcare"></h1></div>
    </div>
    <div id="mv">
        <h2>${nl2br(messages.confirm_success_content000)}<br>${nl2br(messages.confirm_success_content001)}</h2>
        <img src="${process.env.BP_STATIC_URL}${imagePath}mv.png" alt="Success Image">
    </div>
    <div id="main">
        <div id="pic">
            <table id="table-pic">
                <tr>
                        <td>
                            <img id="img_main" src="${process.env.BP_STATIC_URL}${imagePath}main.jpg" alt="あなたの血圧管理をもっと簡単に。OMRON connectアプリ対応">
                        </td>
                        <td>
                            <div id="content_main">
                                <div class="content_main">${nl2br(messages.confirm_success_content005)}</div>
                                <div class="content_main">${nl2br(messages.confirm_success_content006)}</div>
                            </div>
                        </td>
                </tr>
            </table>
        </div>
        <div id="pic2">
            <div id="pic2-content">
                <table id="pic2-tb-sp" class="pic2-tb">
                    <tr>
                        <td width="25%">
                            <img class="sp-image" src="${process.env.BP_STATIC_URL}${imagePath}img_sp-1.jpg" alt="ホーム画面 最新の血圧を確認">
                        </td>
                        <td width="25%">
                            <img class="sp-image" src="${process.env.BP_STATIC_URL}${imagePath}img_sp-2.jpg" alt="グラフ画面 血圧の変化を確認">
                        </td>
                        <td width="25%">
                            <img class="sp-image" src="${process.env.BP_STATIC_URL}${imagePath}img_sp-3.jpg" alt="ホーム画面 最新の血圧を確認">
                        </td>
                        <td width="25%">
                            <img class="sp-image" src="${process.env.BP_STATIC_URL}${imagePath}img_sp-4.jpg" alt="グラフ画面 血圧の変化を確認">
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <div class="content_main-1">${nl2br(messages.confirm_success_content007)}</div>
                        </td>
                        <td width="25%">
                            <div class="content_main-2">${nl2br(messages.confirm_success_content009)}</div>
                        </td>
                        <td width="50%">
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <div class="content_main-1">${nl2br(messages.confirm_success_content008)}</div>
                        </td>
                        <td width="25%">
                            <div class="content_main-2">${nl2br(messages.confirm_success_content010)}</div>
                        </td>
                        <td width="50%"></td>
                    </tr>
                </table>
                <table id="pic2-tb-pc" class="pic2-tb">
                    <tr>
                        <td width="25%">
                            <img class="pc-image" src="${process.env.BP_STATIC_URL}${imagePath}img_pc-1.jpg" alt="ホーム画面 最新の血圧を確認">
                        </td>
                        <td width="25%">
                            <img class="pc-image" src="${process.env.BP_STATIC_URL}${imagePath}img_pc-2.jpg" alt="グラフ画面 血圧の変化を確認">
                        </td>
                        <td width="25%">
                            <img class="pc-image" src="${process.env.BP_STATIC_URL}${imagePath}img_pc-3.jpg" alt="ホーム画面 最新の血圧を確認">
                        </td>
                        <td width="25%">
                            <img class="pc-image" src="${process.env.BP_STATIC_URL}${imagePath}img_pc-4.jpg" alt="グラフ画面 血圧の変化を確認">
                        </td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <div class="content_main-1">${nl2br(messages.confirm_success_content007)}</div>
                        </td>
                        <td width="25%">
                            <div class="content_main-2">${nl2br(messages.confirm_success_content009)}</div>
                        </td>
                        <td width="50%"></td>
                    </tr>
                    <tr>
                        <td width="25%">
                            <div class="content_main-1">${nl2br(messages.confirm_success_content008)}</div>
                        </td>
                        <td width="25%">
                            <div class="content_main-2">${nl2br(messages.confirm_success_content010)}</div>
                        </td>
                        <td width="50%"></td>
                    </tr>
                </table>
            </div>

        </div>
        <div id="lead">
            <p>
            ${nl2br(messages.confirm_success_content002)}<br>
            ${nl2br(messages.confirm_success_content003)}
            </p>
        </div>
        <div id="more_link"><a href="https://www.omronconnect.com">${nl2br(messages.confirm_success_content004)}</a></div>
    </div>
    <div id="foot_link">
        <ul>
            <li><a href="https://www.omronconnect.com/privacy">Privacy Policy</a></li>
            <li><a href="https://www.omronconnect.com/eula">Terms of Use</a></li>
            <li><a href="https://www.omronconnect.com">OMRON connect</a></li>
        </ul>
    </div>
    <div id="foot"><p id="copyright">&copy; OMRON HEALTHCARE Co., Ltd. 2019. All Rights Reserved.</p></div>
</div>
</body>
</html>`;
}
