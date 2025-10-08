import { regularReport } from '../constants/regular-reports';

// Add nl2br helper function
function nl2br(str: string): string {
  return str.replace(/\n/g, '<br />');
}

/**
 * Return the image name for a given risk color and period.
 * The image name format is "risk_levelX_mor" or "risk_levelX_eve",
 * where X is a number between 0 and 4, and period is either "mor" or "eve".
 * @param {string} color the risk color
 * @param {'mor' | 'eve'} period the period of the measurement
 * @returns {string} the image name
 */
function getRiskImageName(color: string, period: 'mor' | 'eve'): string {
  const colorMap: { [key: string]: number } = {
    '#ecedf0': 0,
    '#005F9E': 1,
    '#D1406D': 2,
    '#9b245a': 3,
    '#4f2437': 4
  };

  const level = colorMap[color] ?? 0;
  return `risk_level${level}_${period}`;
}

/**
 * Create a template for regular report emails.
 * @param {string} templateName the name of the template to generate
 * @param {object} context an object containing the context data to fill in the template
 * @param {string} lang the language to use for the email
 * @returns {string} the generated template string
 */
export function createTemplate(templateName: string, context: any, lang: string): string {
  const messages = regularReport[lang] || regularReport.JA; // Fallback to JA if language not found
  
  const welcomeMessage = context.nick 
    ? messages.wellcome.replace('tpl_nickname', context.nick)
    : messages.wellcome.replace('tpl_nickname', '');

  const template = `
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" style="font-size: 16px;-webkit-text-size-adjust: 100%;">
        <head>
            <meta charset="utf-8"/>
            <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
            <meta content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0, maximum-scale=1.0" name="viewport"/>
            <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
        </head>
        <body>
            <div style="margin-left: 3%;">
                <h3>
                    ${welcomeMessage}
                    <br>
                    ${context.measure_times}
                </h3>
                <div class="table_graph" style="max-width: 500px;">
                    <table class="bp-template table-bordered" style="display: block; left: 0; right: 0; border: 1px solid #ddd;border-collapse: collapse;border-spacing: 0; width: 95%; max-width: 500px; max-height: 800px; overflow: hidden;">
                        <thead class="bp-header" style="height: 5%; width: 100%; display: table; vertical-align: middle; text-align: center;">
                            <th class="bp-day bp-day-title" style="width: 25%; display: table-cell; vertical-align: middle; text-align: center; height: 100%; border-bottom-width: 2px; border: 1px solid #ddd; font-size: 16px; border-collapse: collapse; border-spacing: 0; table-layout: fixed; word-break: break-all;">
                                ${messages.bp_day}
                            </th>
                            <th class="bp-measure bp-measure-title" style="width: 60%; display: table-cell; vertical-align: middle; text-align: center; height: 100%; border-bottom-width: 2px; border: 1px solid #ddd; font-size: 16px; border-collapse: collapse; border-spacing: 0; table-layout: fixed; word-break: break-all;">
                                ${messages.bp_measure}
                            </th>
                            <th class="bp-count bp-count-title" style="width: 15%; display: table-cell; vertical-align: middle; text-align: center; height: 100%; border-bottom-width: 2px; border: 1px solid #ddd; font-size: 16px; border-collapse: collapse; border-spacing: 0; table-layout: fixed; word-break: break-all;">
                                ${messages.bp_count}
                            </th>
                        </thead>
                        <tbody class="bp-body" style="width: 100%; display: table; vertical-align: middle; text-align: center; font-size: 16px; font-weight: bold;">
                            ${Object.entries(context.text_dates).map(([day_n, txt_date]) => {
                              const measure_d = context.data_bp[day_n] || {};
                              const morning_image_name = getRiskImageName(measure_d.morning_color || '#ecedf0', 'mor');
                              const evening_image_name = getRiskImageName(measure_d.evening_color || '#ecedf0', 'eve');
                              
                              return `
                                <tr class="bp-item" style="width: 100%; display: table; vertical-align: middle; text-align: center; font-weight: bold;">
                                    <td class="bp-day" style="width: 25%; display: table-cell; vertical-align: middle; text-align: center; height: 100%;border: 1px solid #ddd;border-collapse: collapse; border-spacing: 0; table-layout: fixed; word-break: break-all;">
                                        <span style="display: block; font-weight: bold;">
                                            ${context.text_md[day_n]}
                                            <br/>
                                            ${context.text_dates[day_n]}
                                        </span>
                                    </td>
                                    <td class="bp-measure" style="width: 60%; display: table-cell; vertical-align: middle; text-align: center; height: 100%;border: 1px solid #ddd;border-collapse: collapse; border-spacing: 0; table-layout: fixed; word-break: break-all;">
                                        <table width="100%" height="100%" style="text-align: center;">
                                            <tr style="width: 100%; height: 50%; vertical-align: bottom" width="100%" height="50%">
                                                <td style="width: 30%" width="30%">
                                                    <img src="${process.env.BP_STATIC_URL}public/imgs/app/${morning_image_name}.png" style="display: block; margin: 0 auto;" width="43" height="20"/>
                                                </td>
                                                <td style="width: 30%" width="30%">
                                                    <table width="100%" height="100%" style="text-align: center;">
                                                        <tr style="width: 100%; height: 100%; vertical-align: bottom" width="100%" height="100%">
                                                            <td style="width: 33.33%" width="33.33%"></td>
                                                            <td style="width: 33.33%" width="33.33%"></td>
                                                            <td style="width: 33.33%" width="33.33%">
                                                                <img src="${process.env.BP_STATIC_URL}public/imgs/app/morning_gray_icon.png" style="display: block; margin-left: auto; margin-right: 0;" width="20" height="20"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width: 40%" width="40%">
                                                    <span>${measure_d.morning || '--/--'}</span>
                                                </td>
                                            </tr>
                                            <tr style="vertical-align: top; width: 100%; height: 50%;" width="100%" height="50%">
                                                <td style="width: 30%" width="30%">
                                                    <img src="${process.env.BP_STATIC_URL}public/imgs/app/${evening_image_name}.png" style="display: block; margin: 0 auto;" width="43" height="20"/>
                                                </td>
                                                <td style="width: 30%" width="30%">
                                                    <table width="100%" height="100%" style="text-align: center;">
                                                        <tr style="width: 100%; height: 100%; vertical-align: bottom" width="100%" height="100%">
                                                            <td style="width: 33.33%" width="33.33%"></td>
                                                            <td style="width: 33.33%" width="33.33%"></td>
                                                            <td style="width: 33.33%" width="33.33%">
                                                                <img src="${process.env.BP_STATIC_URL}public/imgs/app/evening_gray_icon.png" style="display: block; margin-left: auto; margin-right: 0;" width="20" height="20"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width: 40%" width="40%">
                                                    <span>${measure_d.evening || '--/--'}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="bp-count" style="width: 15%; display: table-cell; vertical-align: middle; text-align: center; height: 100%;border: 1px solid #ddd;border-collapse: collapse; border-spacing: 0; table-layout: fixed; word-break: break-all;">
                                        <span>${measure_d.count || 0}</span>
                                    </td>
                                </tr>
                              `;
                            }).join('')}
                        </tbody>
                    </table>
                    ${context.relogin_regular_report_message ? `
                        <h4>━━━━━━━━━━━━━━━━━━<br/></h4>
                        <h4 style="color: #c30d25; margin-bottom: 0;">
                            ${nl2br(messages.relogin_regular_report_message_1)}
                        </h4>
                        <h4 style="font-weight: normal; color: #c30d25; margin-top: 5px">
                            <span>${nl2br(messages.relogin_regular_report_message_2)}</span>
                            <span style="text-decoration: underline;">${nl2br(messages.relogin_regular_report_message_3)}</span>
                        </h4>
                        <h4 style="color: #c30d25; margin-bottom: 0">
                            ${nl2br(messages.relogin_regular_report_message_4)}
                        </h4>
                        <h4 style="font-weight: normal; color: #c30d25; margin-top: 5px">
                            ${nl2br(messages.relogin_regular_report_message_5)}
                        </h4>
                    ` : ''}
                    <div class="bp_footer">
                        ${nl2br(messages.footer).replace('%s', context.link_unsubscribe)}
                    </div>
                </div>
            </div>
        </body>
    </html>
  `;

  return template;
}
