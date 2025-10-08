<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use App\Mail\BPNoteBook;
use App\Utils\MeasureUtils;
use App\Model\SettingsModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Model\MemoOrdersModel;
use Config;

/**
 * SendMailController.php
 * NewBP
 *
 * Created by TanND on 2017/10/13.
 * Copyright (c) 2017年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */
class SendMailController extends Controller
{
    private $userSetting;
    private $header;
    private $params;
    private $errCode = '';
    private $message = [];
    private $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel;
        // header, params for test
        $this->header = [
            'Authorization' => base64_encode(\Config::get('constants.API_KEY')),
            'Content-Type'  => 'application/json',
        ];
    }

	/**
    * Function bp_notebook send mail from data
    *
    * @param Request $request
    * @return mixed
    * @throws \Throwable
    */
    public function bp_notebook(Request $request)
    {
        $this->defaultWriteLog('Requesting for sending email.');
        $traceID = $this->traceID();
        $this->startWriteLog($traceID);

        ini_set('memory_limit', '-1');
        $body   = $request->all();
        $output = [
            'success' => false,
            // 'message' => '',
        ];

        $rule = [
            'type'         => 'required|numeric|in:0,1',
            'mail'         => 'required|string|email',
            'traceId'      => 'string|nullable',
            'screenName'   => 'string|nullable',
            'start_date'   => 'required|string|before:end_date',
            'end_date'     => 'required|string|after:start_date',
            'user_name'    => 'string|nullable',
            'lang'         => 'string',
            'country'      => 'string',
            'input_name'   => 'string|nullable',
            'time_create'  => 'required|string|date_format:"Y-m-d H:i"',
        ];

        $validator = \Validator::make($body, $rule);
        $error_v   = $validator->errors()->getMessages();
        foreach ($error_v as $key => $value) {
            // 改造BP_APP_DEV-1222 2020.04.17 TranHV ---->
            // Change parameter because function change in php 7
            $error_v[$key] = join(',', $value);
            // BP_APP_DEV-1222 2020.04.17 <----
        }

        // check user esixts or none
        $user_id     = $request->get('user')['userID'] ?? '';
        $userSetting = SettingsModel::where('user_id', $user_id)->first();
        if ($userSetting) {
            $this->userSetting = $userSetting;
            // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
            // Check user set systolic_taget and diastolic_taget
            !isset($this->userSetting->systolic_target) && ($this->userSetting->systolic_target = config('constants.DEFAULT_SYS'));
            !isset($this->userSetting->diastolic_target) && ($this->userSetting->diastolic_target = config('constants.DEFAULT_DIA'));
            // 改造BP_APP_DEV-2760 2024.05.29 <----
            
        } else {
            $error_v['user_id'] = empty($error_v['user_id']) ? '' : $error_v['user_id'];
            // 改造BP_APP_DEV-1222 2020.04.17 TranHV ---->
            // Change parameter because function change in php 7
            $error_v['user_id'] = join(', ', [$error_v['user_id'], 'WRONG_USER_ID']);
            // 改造BP_APP_DEV-1222 2020.04.17 <----
        }
        // 改造BP_APP_DEV-1694 2021.10.14 TrungNT ---->
        // check username format (letter, number, _, -, .)
        if (!preg_match('/^[\w.-]*$/', $request->get('user_name'))) {
            $error_v['user_name_security'] = join(['USER_NAME_NOT_STRONG_ENOUGH']);
        }
        // 改造BP_APP_DEV-1694 2021.10.14 <----
        if (!empty($error_v)) {
            $output['message'] = $error_v;
            $this->endWriteLog($traceID, $output);
            return response()->json($output);
        }

        $access_token = $request->get('user')['token'];
        $type         = $request->get('type');
        $mail         = $request->get('mail');
        $screenName   = $request->get('screenName');
        $start_date   = $request->get('start_date');
        $end_date     = $request->get('end_date');
        $input_name   = $request->get('input_name');
        $user_name    = $request->get('user_name');
        $user_name    = $user_name ? $user_name : \Config::get('constants.mail_to_name');
        $lang         = strtoupper($request->get('lang'));
        $lang         = config('constants.' . $lang) ? $lang : 'EN';
        $country      = strtoupper($request->get('country'));
        $time_create  = $request->get('time_create');

        // Get template prefix folder, font name and font file
        $sub_blade = '.' . strtolower($lang);
        // 改造BP_APP_DEV-1149 2020.02.12 AnhNVT ---->
        switch (strtolower($lang)) {
            case 'ja':
                $font_lang = ['ipaexg', 'ipaexg-emoji.ttf'];
                break;
            case 'en':
                $font_lang = ['ipagp', 'ipapgothic-emoji.ttf'];
                break;
            case 'zh':
                $font_lang = ['twsung', 'TW-Sung-98_1-emoji.ttf'];
                break;
            // 改造BP_APP_DEV-1380 2020.09.9 MinhTQ ---->
            // add case for simplified chinese
            case 'zh-hans':
                $font_lang = ['twsung', 'TW-Sung-98_1-emoji.ttf'];
                break;
            // BP_APP_DEV-1380 2020.09.9 <----
            case 'ko':
                $font_lang = ['gothica1', 'GothicA1-Regular-emoji.ttf'];
                break;
                // 改造BP_APP_DEV-1076 2019.06.13 TranHV ---->
            case 'de':
            case 'fr':
            case 'it':
            case 'es':
            case 'nl':
            case 'pl':
            case 'sv':
            case 'tr':
            // 改造BP_APP_DEV-2009 2022.07.11 AnhNVT ---->
            case 'vi':
            // 改造BP_APP_DEV-2009 2022.07.11 AnhNVT <----
            // 改造BP_APP_DEV-1094 2019.06.13 ThanhDKN ---->
            case 'pt':
            // BP_APP_DEV-1094 2019.06.13 <----
                // BP_APP_DEV-1076 2019.06.13 <----
                $font_lang = ['notosans', 'NotoSans-Regular-emoji.ttf'];
                break;
            default:
                $font_lang = ['ipagp', 'ipapgothic-emoji.ttf'];
                break;
        }
        // BP_APP_DEV-1149 2020.02.12 AnhNVT <----

        // parameters for call api get vitals data
        $this->params = [
            "access_token"    => $access_token,
            "start_date_time" => $start_date,
            "end_date_time"   => $end_date,
            "user_id"         => $user_id,
            "screenName"      => $screenName,
            "user_name"       => $input_name,
            "lang"            => $lang,
            "time_create"     => $time_create,
            "font_lang"       => $font_lang,
            "sub_blade"       => $sub_blade,
        ];

        // if type 0 generate PDF
        // else generate CSV
        if ($type == 0) {
            # pdf
            $path_pdf_csv = $this->generatePDFFile();
        } else {
            # csv
            $path_pdf_csv = $this->measureToStringCsv();
        }
        dd(1);
        $attachment = null;
        // if have error or dont have pdf/csv
        // then return error
        // read file and create link to file
        if (!empty($this->errCode) || empty($path_pdf_csv) || !file_exists($path_pdf_csv)) {
            $output['message']  = $this->message;
            $output['err_code'] = $this->errCode;
            $this->endWriteLog($traceID, $output);
            return response()->json($output);
        } else {
            $file       = fopen($path_pdf_csv, "rb");
            $attachment = fread($file, filesize($path_pdf_csv));
            fclose($file);
            file_exists($path_pdf_csv) && unlink($path_pdf_csv);
        }

        $to = $mail;

        // create content mail
        $subject         = \Config::get('constants.' . $lang . '.title');
        $prefix_filename = \Config::get('constants.mail_pre_file.' . $lang);
        $start_date      = date('Ymd', strtotime($start_date));
        $end_date      = date('Ymd', strtotime($end_date));
        $filename        = $prefix_filename . $start_date . '-' . $end_date . ($type == 0 ? '.pdf' : '.csv');

        $from = \Config::get('constants.mail_from.' . $country);
        if (!$from) {
            $from = \Config::get('constants.mail_from.JP');
        }
        $bcc_list = \Config::get('constants.mail_bcc');

        $from_mail  = \Config::get('constants.mail_from2');
        // 改造BP_APP_DEV-3841 2024.07.25 LoiNV ---->
        $reply_mail = $from_mail;
        $tpl_mail = \Config::get('constants.mail_reply');
        // 改造BP_APP_DEV-3841 2024.07.25 <----

        $body = \Config::get('constants.' . $lang . '.body');
        // 改造BP_APP_DEV-3841 2024.07.25 LoiNV ---->
        $body = str_replace(['tpl_user', 'tpl_filename', 'tpl_mail'], [$user_name, $filename, $tpl_mail], $body);
        // 改造BP_APP_DEV-3841 2024.07.25 <----

        // mail by laravel ses
        $data = [
            'mailFrom'    => $from_mail,
            'emailTo'     => $to,
            'emailToName' => $user_name,
            'bcc_list'    => empty($bcc_list) ? null : explode(',', $bcc_list),
            'reply_mail'  => $reply_mail,
            'subject'     => $subject,
            'body'        => $body,
            'filename'    => $filename,
            'attachment'  => $attachment,
        ];

        $this->startWriteLog('Sending Mail: Started');
        // send mail
        $mailSending = Mail::to($data['emailTo'], $data['emailToName']);

        !empty($data['bcc_list']) && $mailSending = $mailSending->bcc($data['bcc_list']);
        // try sending mail to user
        try {
            $mailSending = $mailSending->send(new BPNoteBook($data));
            $output['success'] = !!$mailSending;
            $this->endWriteLog('Sending Mail: Success', []);
        } catch (\Exception $e) {
            $output['success'] = false;
            $this->endWriteLog('Sending Mail: Error: ', $e);
        }

        if (!$output['success']) {
            $output['message'] = 'Failed to send a request mail. Please try again.';
        }
        $this->endWriteLog($traceID, $output);
        return response($output)->header('Content-Type', 'application/json');
    }

    // NEW METHOD GENERATE SVG FROM DATA VITALS DATA

   /**
    * GeneratePDFFile generate PDF from data vitals
    *
    * @return string
    * @throws \Throwable
    */
    public function generatePDFFile()
    {
        $header = $this->header;
        $params = $this->params;
        if (empty($this->userSetting)) {
            $this->userSetting = SettingsModel::where('user_id', $params['user_id'])->first();
        }

        // get params from request
        $date_from   = date('Y-m-d H:i:00', strtotime($params['start_date_time']));
        $date_to     = date('Y-m-d H:i:59', strtotime($params['end_date_time']));
        $date_to_data     = date('Y-m-d H:i:59', strtotime($params['end_date_time']));
        $user_name   = $params['user_name'];
        $lang        = $params['lang'];
        $time_create = $params['time_create'];
        $sub_blade   = $params['sub_blade'];
        $app_lang    = \Config::get('constants.' . $lang);
        $time_create = date($app_lang['PDF_DATE_OUTPUT'], strtotime($time_create));
        if (empty($user_name)) {
            // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
            $user_name = str_repeat('&nbsp;', 5);
            // BP_APP_DEV-1059 2018.08.23 <----
        }

        // 改造BP_APP_DEV-1060 2019.06.28 TranHV ---->
        $start_mor = $this->userSetting->bp_morning_start;
        $end_mor   = $this->userSetting->bp_morning_end;
        $start_eve = $this->userSetting->bp_evening_start;
        $end_eve   = $this->userSetting->bp_evening_end;
        $time_overday =[
            'mor_end'   => false,
            'eve_start' => false,
            'eve_end'   => false,
        ];
        if($start_mor >= $end_mor){ // End moring next day
            $time_overday['mor_end']    = true;
            $time_overday['eve_start']  = true;
            $time_overday['eve_end']    = true;
            $date_to_data = date('Y-m-d '.$end_eve, strtotime('+1 days', strtotime($params['end_date_time'])));
        }else{ // Morning same day
            if($start_eve >= $end_eve){ // End evening next day
                $time_overday['eve_end']    = true;
                $date_to_data = date('Y-m-d '.$end_eve, strtotime('+1 days', strtotime($params['end_date_time'])));
            }else{
                if($start_eve <= $end_mor){
                    $time_overday['eve_start']  = true;
                    $time_overday['eve_end']    = true;
                    $date_to_data = date('Y-m-d '.$end_eve, strtotime('+1 days', strtotime($params['end_date_time'])));
                }
            }
        }
        $params['date_to_data'] = $date_to_data;
        $this->params['date_to_data'] = $date_to_data;
        // BP_APP_DEV-1060 2019.06.28 <----

        // get data vitals and fill for each day
        // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
        // Check user set systolic_taget and diastolic_taget
        $sys_t = !isset($this->userSetting->systolic_target) ? config('constants.DEFAULT_SYS') : $this->userSetting->systolic_target;
        $dia_t = !isset($this->userSetting->diastolic_target) ? config('constants.DEFAULT_DIA') : $this->userSetting->diastolic_target;
        // 改造BP_APP_DEV-2760 2024.05.29 <----
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        $time_period = [
            'mor_start' => date('G:i', strtotime($this->userSetting->bp_morning_start ?? '')),
            'mor_end' => date('G:i', strtotime($this->userSetting->bp_morning_end ?? '')),
            'eve_start' => date('G:i', strtotime($this->userSetting->bp_evening_start ?? '')),
            'eve_end' => date('G:i', strtotime($this->userSetting->bp_evening_end ?? '')),
        ];

        // BP_APP_DEV-1059 2018.08.23 <----
        // get and group data vital by date
        $data_xm = $this->dataVitalByDate($header, $params);
        // arr_day
        $data_vitals = $data_xm['arr_day'];
        if (empty($data_vitals)) {
            return null;
        }
        // create each svg files, max 16 days/file
        // 改造BP_APP_DEV-1060 2019.08.22 TranHV ---->
        $date_from_p = date('Y-m-d', strtotime($date_from));
        $date_to_p   = date('Y-m-d', strtotime($date_to));
        $date_to_e   = date('Y-m-d', strtotime('+1 days', strtotime($date_to)));
        // BP_APP_DEV-1060 2019.08.22 <----
        $count_day   = function ($start_d, $end_d) {
            $date1 = new \DateTime($start_d);
            $date2 = new \DateTime($end_d);
            return (int) $date2->diff($date1)->format("%a") + 1;
        };
        // total day will print in report each day
        $total_days = $count_day($date_from_p, $date_to_p);

        $cnt             = 0;
        $arr_days        = [];
        $arr_weeks       = [];
        $arr_avg_5_weeks = [];
        $page_days       = 0;
        $page_w          = 0;
        $avg_week        = [];
        $num_date_med    = ['mor' => 0, 'eve' => 0];
        $arr_day_done    = false;

        // average value pressure per day
        $calculateAverage = function ($array, $type = 'mor', $key = 'systolic') {
            if (empty($array[$type])) {
                return '';
            }
            $array = $array[$type];
            $sum   = count($array);
            $r     = [];
            foreach ($array as $v) {
                $r[] = $v[$key];
            }
            return round(array_sum($r) / $sum);
        };

        // loop each day
        while ($date_from_p <= $date_to_e) {
            $arr_day_done = ($date_from_p > $date_to_p);
            // show text date by lang, first day of page or of month
            if (!$arr_day_done) {
                $format_day = 'j'; // 1->31
                if (!isset($arr_days[$page_days]) || date('d', strtotime($date_from_p)) == '01') {
                    $format_day = $app_lang['PDF_MONTH_FORMAT'];
                }
                // text week en or ja: Mon, Tue, Wed, ...
                $week_d    = date('N', strtotime($date_from_p));
                $is_red    = ($week_d == 7);
                $week_date = $app_lang['PDF_DATES'][$week_d - 1];

                // create array data days per page
                $arr_days[$page_days][] = [
                    'day'         => date($format_day, strtotime($date_from_p)),
                    'weekdays'    => $week_date,
                    'is_red'      => $is_red,
                    'date_from_p' => $date_from_p,
                    'vitals'      => !empty($data_vitals[$date_from_p]) ? $data_vitals[$date_from_p] : [],
                ];
                if (count($arr_days[$page_days]) % 16 == 0) {
                    // Reverse thứ tự 16 ngày vừa được thêm vào
                    // $current_page_data = array_splice($arr_days[$page_days], -16, 16);
                    // $arr_days[$page_days] = array_merge($arr_days[$page_days], array_reverse($current_page_data));
                    $page_days++;
                }
            }

            // column week
            if (($cnt && $cnt % 7 == 0) || $arr_day_done) {
                if ($arr_day_done) {
                    $num_day_else = intval($cnt % 7);
                    $num_day_else = $num_day_else ? $num_day_else : 7;
                    $start_w      = date('Y-m-d', strtotime("-{$num_day_else} days", strtotime($date_to_e)));
                } else {
                    $start_w = date('Y-m-d', strtotime('-7 days', strtotime($date_from_p)));
                }
                // text date from - to 1 week show each column
                $d_start_x = date('Y-m-d', strtotime($start_w));
                $d_end_x   = date('Y-m-d', strtotime('-1 days', strtotime($date_from_p)));

                $d_start_w = date($app_lang['PDF_WEEK_FORMAT'], strtotime($d_start_x));
                $d_end_w   = date($app_lang['PDF_WEEK_FORMAT'], strtotime($d_end_x));

                $days_in_w   = $count_day($d_start_x, $d_end_x);
                $a_mor_sys   = $calculateAverage($avg_week, 'mor', 'systolic');
                $a_mor_dia   = $calculateAverage($avg_week, 'mor', 'diastolic');
                $a_mor_pulse = $calculateAverage($avg_week, 'mor', 'pulse');
                $a_eve_sys   = $calculateAverage($avg_week, 'eve', 'systolic');
                $a_eve_dia   = $calculateAverage($avg_week, 'eve', 'diastolic');
                $a_eve_pulse = $calculateAverage($avg_week, 'eve', 'pulse');
                $a_mor_d_p_w = ($num_date_med['mor'] && $days_in_w) ? $num_date_med['mor'] . '/' . $days_in_w : '';
                $a_eve_d_p_w = ($num_date_med['eve'] && $days_in_w) ? $num_date_med['eve'] . '/' . $days_in_w : '';

                $arr_weeks[$page_w][] = [
                    'display_1w' => $d_start_w . $app_lang['PDF_DATE_SEPARATE'] . $d_end_w,
                    'vitals'     => [
                        'mor' => [
                            'systolic'  => $a_mor_sys,
                            'diastolic' => $a_mor_dia,
                            'pulse'     => $a_mor_pulse,
                            'd_p_w'     => $a_mor_d_p_w,
                        ],
                        'eve' => [
                            'systolic'  => $a_eve_sys,
                            'diastolic' => $a_eve_dia,
                            'pulse'     => $a_eve_pulse,
                            'd_p_w'     => $a_eve_d_p_w,
                        ],
                    ],
                ];
                $num_date_med                      = ['mor' => 0, 'eve' => 0];
                $end_data_w                        = end($arr_weeks[$page_w])['vitals'];
                $arr_avg_5_weeks[$page_w]['mor'][] = $end_data_w['mor'];
                $arr_avg_5_weeks[$page_w]['eve'][] = $end_data_w['eve'];
                $avg_week                          = [];
                if ($total_days <= 7) {
                    break;
                }
            }
            // Data week: if total days <7 in first loop OR loop 7 days/week
            if (!empty($data_vitals[$date_from_p])) {
                $vt = $data_vitals[$date_from_p];
                !empty($vt['mor']) && ($avg_week['mor'][] = $vt['mor']);
                !empty($vt['eve']) && ($avg_week['eve'][] = $vt['eve']);
                !empty($vt['mor']) && $num_date_med['mor']++;
                !empty($vt['eve']) && $num_date_med['eve']++;
            }

            // page week
            $count_w_per_p = isset($arr_weeks[$page_w]) ? count($arr_weeks[$page_w]) : 0;
            if ($count_w_per_p && $count_w_per_p % 5 == 0 && !$arr_day_done) {
                $page_w++;
            }
            $cnt++;
            // 改造BP_APP_DEV-1060 2019.08.22 TranHV ---->
            $date_from_p = date('Y-m-d', strtotime('+1 days', strtotime($date_from_p)));
            // BP_APP_DEV-1060 2019.08.22 <----
        };

        // Reverse page cuối cùng nếu có dữ liệu
        // if (isset($arr_days[$page_days]) && count($arr_days[$page_days]) > 0) {
        //     $arr_days[$page_days] = array_reverse($arr_days[$page_days]);
        // }

        $avg_days_data = [];
        foreach ($arr_days as $index_page => $item) {
            foreach ($item as $key => $value) {
                $avg_days_data[$value['date_from_p']]['average'] = empty($value['vitals']['average']) ? [] : $value['vitals']['average'];
            }
        }
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        $all_day_reports = $this->allDataVitalByDate();
        // dd($all_day_reports);
        // get data by date
        $arr_day_reports = $all_day_reports['arr_day'];
        // group data by week
        $arr_week_reports = $this->buildWeek($data_vitals, $avg_days_data);
        $mode = $all_day_reports['mode'];
        $count_bp_days = 0;
        // total day
        // if not default 1
        foreach($arr_day_reports as $index_day => $day){
            $count_bp_days += (count($day['measures']) == 0) ? 1 : count($day['measures']);
        }
        // define how many page export
        $count_page = $this->caclPage(count($arr_week_reports), $count_bp_days, $mode);
        $total_page = $count_page + count($arr_days)*2;
        $page = 0;
        // BP_APP_DEV-1059 2018.08.23 <----
        // Create svg by day
        $svgArray = [];
        $font_lang = $this->params['font_lang'];
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        // get setting medication per day
        $medicinesPerDay = $this->userSetting->medicine_per_day;
        // 改造BP_APP_DEV-2760 2024.06.04 LoiNV ---->
        // Get level lines from morning, evening data
        $level_line  = $this->getMorEveMaxMinValue($arr_days);
        $level_label = $level_line['valueLabel'];
        // 改造BP_APP_DEV-2760 2024.06.04 <----

        /**
         * Data for page morning and evening per day and create SVG for morning and evening per day
         */
        foreach ($arr_days as $index_page => $item) {
            $itemWithoutAvg = [];
            $page++;
            foreach ($item as $key => $value) {
                if (array_key_exists('average', $value['vitals'])) {
                    unset($value['vitals']['average']);
                }
                $itemWithoutAvg[] = $value;
            }
            // BP_APP_DEV-1059 2018.08.23 <----
            $data = [
                'date_from'   => date($app_lang['PDF_DATE_FORMAT'], strtotime($date_from)),
                'date_to'     => date($app_lang['PDF_DATE_FORMAT'], strtotime($date_to)),
                'time_create' => $time_create,
                'user_name'   => $user_name,
                'app_lang'    => $app_lang,
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                'index_page'  => $page,
                // BP_APP_DEV-1059 2018.08.23 <----
                'total_page'  => $total_page,
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                'arr_days'    => $itemWithoutAvg,
                // BP_APP_DEV-1059 2018.08.23 <----
                'target_bp'   => ['sys_t' => $sys_t, 'dia_t' => $dia_t],
                'lang'        => $this->params["lang"],
                'level_label' => $level_label,
                'font_file'   => public_path('/fonts/' . $font_lang[1]),
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                'font_name'   => $font_lang[0],
                'medicines_per_day' => $medicinesPerDay,
                'user_id'     => $params['user_id'],
                'time_period' => $time_period,
                'time_overday' => $time_overday,
                'sub_blade'   => $sub_blade
                // BP_APP_DEV-1059 2018.08.23 <----
            ];
            // create content pdf for day
            $contents = view("svg{$sub_blade}.day", $data)->render();
            // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
            $svgArray[] = $contents;
            $count_page++;
        }

        // 改造BP_APP_DEV-2760 2024.06.04 LoiNV ---->
        // Get level lines from average data
        $level_line  = $this->getAvgMaxMinValue($arr_days);
        $level_label = $level_line['valueLabel'];
        // 改造BP_APP_DEV-2760 2024.06.04 <----

        /**
         * Data for page average per day and create SVG for average per day
         */
        foreach ($arr_days as $index_page => $item) {
            $itemWithAvg = [];
            foreach ($item as $key => $value) {
                if (isset($value['vitals']['mor'])) {
                    unset($value['vitals']['mor']);
                }
                if (isset($value['vitals']['eve'])) {
                    unset($value['vitals']['eve']);
                }
                $itemWithAvg[] = $value;
            }
            
            $page++;
            $dataAverage = [
                'date_from'   => date($app_lang['PDF_DATE_FORMAT'], strtotime($date_from)),
                'date_to'     => date($app_lang['PDF_DATE_FORMAT'], strtotime($date_to)),
                'time_create' => $time_create,
                'user_name'   => $user_name,
                'app_lang'    => $app_lang,
                'index_page'  => $page,
                'total_page'  => $total_page,
                'arr_days'    => $itemWithAvg,
                'target_bp'   => ['sys_t' => $sys_t, 'dia_t' => $dia_t],
                'lang'        => $this->params["lang"],
                'level_label' => $level_label,
                'font_file'   => public_path('/fonts/' . $font_lang[1]),
                'font_name'   => $font_lang[0],
                'medicines_per_day' => $medicinesPerDay,
                'user_id'     => $params['user_id'],
                'time_period' => $time_period,
                'sub_blade'   => $sub_blade
            ];
            // create content pdf for avarage day
            $contents = view("svg{$sub_blade}.day_average", $dataAverage)->render();
            // BP_APP_DEV-1059 2018.08.23 <----
            // echo $contents;die;
            $svgArray[] = $contents;
            // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
            $count_page++;
            // BP_APP_DEV-1059 2018.08.23 <----
        }
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        $start_page = count($arr_days)*2;
        // create content pdf for week average
        $svgArray = array_merge($svgArray, $this->generateWeekAvg($arr_day_reports, $arr_week_reports, $avg_days_data, $count_page,  $start_page, $mode));
        
        return $this->Svg2Pdf($svgArray);
        // BP_APP_DEV-1059 2018.08.23 <----
    }
    // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->

    /**
     * @param array $arr_days: data vital group by date
     * @param array $arr_weeks: data vital group by week
     * @param array $avg_days_data: todo should delete? not use anymore
     * @param int $total_page: total page for this data
     * @param int $start_page: start page in pdf file
     * @param string $mode: mode pdf A|B
     * @return array
     * @throws \Throwable
     */
    public function generateWeekAvg($arr_days = [], $arr_weeks = [], $avg_days_data = [], $total_page = 0 , $start_page = 0, $mode = 'A')
    {
        $params = $this->params;
        $lang        = $params['lang'];
        $app_lang    = \Config::get('constants.' . $lang);
        $date_from   = date('Y-m-d H:i:00', strtotime($params['start_date_time']));
        $date_to     = date('Y-m-d H:i:59', strtotime($params['end_date_time']));
        $font_lang = $this->params['font_lang'];
        $medicinesPerDay = $this->userSetting->medicine_per_day;
        $user_name   = $params['user_name'];
        $time_create = $params['time_create'];
        $sub_blade = $params['sub_blade'];
        $time_create = date($app_lang['PDF_DATE_OUTPUT'], strtotime($time_create));
        $icon_sun  = file_get_contents(resource_path('assets/icon/sun.txt'));
        $icon_moon = file_get_contents(resource_path('assets/icon/moon.txt'));
        $icon_day = file_get_contents(resource_path('assets/icon/sun_full.txt'));
        $icon_medicine = file_get_contents(resource_path('assets/icon/medicine.txt'));
        $icon_medicine_active = file_get_contents(resource_path('assets/icon/medicine_active.txt'));
        // icon for vital data type
        $arrIconImage = [
            '-'                    => null,
            'icon_target_achieved' => file_get_contents(resource_path('assets/icon/target_achieved.txt')),
            'icon_equal'           => file_get_contents(resource_path('assets/icon/equal.txt')),
            'icon_higher'          => file_get_contents(resource_path('assets/icon/higher.txt')),
            'icon_much_higher'     => file_get_contents(resource_path('assets/icon/much_higher.txt')),
            'icon_arrow_target'    => file_get_contents(resource_path('assets/icon/arrow_target.txt')),
            'icon_consultation'    => file_get_contents(resource_path('assets/icon/consultation.txt')),
            'icon_medicine'        => file_get_contents(resource_path('assets/icon/medicine.txt')),
            'icon_medicine_active' => file_get_contents(resource_path('assets/icon/medicine_active.txt')),
            'mode'                 => file_get_contents(resource_path('assets/icon/mode.txt')),
            'moon_sleep'           => file_get_contents(resource_path('assets/icon/moon_sleep.txt')),
            'measurement_error'    => file_get_contents(resource_path('assets/icon/measurement_error.txt')),
            'irregular_heartbeat'  => file_get_contents(resource_path('assets/icon/irregular_heartbeat.txt')),
            'afib'                 => file_get_contents(resource_path('assets/icon/afib.txt')),
            // 改造BP_APP_DEV-4288 2025.02.04 LoiNV ---->
            'irregular_pulse_interval' => file_get_contents(resource_path('assets/icon/irregular_pulse_interval.txt')),
            // 改造BP_APP_DEV-4288 2025.02.04 <----
        ];
        // array icon activity
        $arrIconNotes = [
            'un_smoking'    => file_get_contents(resource_path('assets/icon/not_smoking.txt')),
            'smoking'       => file_get_contents(resource_path('assets/icon/smoking.txt')),
            'un_drink'    => file_get_contents(resource_path('assets/icon/no_alcohol.txt')),
            'drink'       => file_get_contents(resource_path('assets/icon/alcohol.txt')),
            'un_salt'     => file_get_contents(resource_path('assets/icon/reducing_salt.txt')),
            'salt'        => file_get_contents(resource_path('assets/icon/salt.txt')),
            'veggie'      => file_get_contents(resource_path('assets/icon/vegetables.txt')),
            'un_veggie'   => file_get_contents(resource_path('assets/icon/less_vegetables.txt')),
            'sleep'       => file_get_contents(resource_path('assets/icon/sleep.txt')),
            'un_sleep'    => file_get_contents(resource_path('assets/icon/lack_of_sleep.txt')),
            'exercise'    => file_get_contents(resource_path('assets/icon/exercise.txt')),
            'un_exercise' => file_get_contents(resource_path('assets/icon/lack_of_exercise.txt')),
            'hospital'    => file_get_contents(resource_path('assets/icon/hospital.txt')),
        ];
        $data_template = [
            'date_from'            => date($app_lang['PDF_DATE_FORMAT'], strtotime($date_from)),
            'date_to'              => date($app_lang['PDF_DATE_FORMAT'], strtotime($date_to)),
            'app_lang'             => $app_lang,
            'lang'                 => $lang,
            'font_file'            => public_path('/fonts/' . $font_lang[1]),
            'font_name'            => $font_lang[0],
            'icon_sun'             => $icon_sun,
            'icon_moon'            => $icon_moon,
            'icon_day'             => $icon_day,
            'user_name'            => $user_name,
            'time_create'          => $time_create,
            'icon_medicine'        => $icon_medicine,
            'icon_medicine_active' => $icon_medicine_active,
            'arr_icon_images'      => $arrIconImage,
            'arr_icon_notes'       => $arrIconNotes,
            'mode'                 => $mode,
            'total_page'           => $total_page,
            'sub_blade'            => $sub_blade,
            'med_per_day'          => $medicinesPerDay,
        ];
        $svgArray = [];
        $count_bp = 0;
        foreach ($arr_days as $index_day => $day) {
            $count_bp += (count($day['measures']) == 0) ? 1 : count($day['measures']);
        }
        $week_sheet_max_row = 23;
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        $day_head_sheet_max_row = 14;
        // 改造BP_APP_DEV-4288 2025.02.11 <----

        if (count($arr_weeks) <= $week_sheet_max_row && $count_bp <= $day_head_sheet_max_row) {
            $start_page++;
            $data = array_merge([
                'arr_weeks'    => $arr_weeks,
                'arr_days'     => $arr_days,
                'index_page'   => $start_page,
            ], $data_template);
            $contents   = view("svg{$sub_blade}.week_day", $data)->render();
            $svgArray[] = $contents;
        } else {
            if (count($arr_weeks) > $week_sheet_max_row && count($arr_weeks) <= 2*$week_sheet_max_row) {
                $start_page++;
                $data = array_merge([
                    'arr_weeks'    => $arr_weeks,
                    'arr_days'     => $arr_days,
                    'index_page'   => $start_page,
                ], $data_template);
                $contents   = view("svg{$sub_blade}.week_week", $data)->render();
                $svgArray[] = $contents;

                // Draw days
                $days = $this->buildDays($arr_days, $mode);
                foreach($days as $day_idx => $page_day){
                    $start_page++;
                    $data = array_merge([
                        'left_days'     => $page_day['left'],
                        'right_days'    => $page_day['right'],
                        'index_page'   => $start_page,
                    ], $data_template);
                    $contents   = view("svg{$sub_blade}.day_day", $data)->render();
                    $svgArray[] = $contents;
                }
                return $svgArray;
            } else {
                $count_week = count($arr_weeks);

                $count_sheet_week = (int)(floor($count_week/$week_sheet_max_row));
                $count_remain_week = $count_week%$week_sheet_max_row;

                if ($count_sheet_week % 2 == 0 && $count_sheet_week > 0) {
                    $weeks = array_chunk($arr_weeks, 2*$week_sheet_max_row);
                    foreach ($weeks as $index_page => $item) {
                        if(count($weeks[$index_page]) == 2*$week_sheet_max_row){
                            $start_page++;
                            $data = array_merge([
                                'arr_weeks'     => $weeks[$index_page],
                                'index_page'   => $start_page,
                            ], $data_template);
                            $contents   = view("svg{$sub_blade}.week_week", $data)->render();
                            $svgArray[] = $contents;
                        }
                    }
                }
                $remain_week = [];
                if ($count_sheet_week > 1) {
                    $week_split = array_chunk($arr_weeks, $count_sheet_week*$week_sheet_max_row);
                    if (count($week_split) > 1) {
                        $remain_week = $week_split[1];
                    }
                } else {
                    $remain_week = $arr_weeks;
                }

                if ($count_remain_week == 0 && $count_sheet_week % 2 == 0 && $count_sheet_week > 0) {
                    // Draw days
                    $days = $this->buildDays($arr_days, $mode);
                    foreach ($days as $day_idx => $page_day) {
                        $start_page++;
                        $data = array_merge([
                            'left_days'     => $page_day['left'],
                            'right_days'    => $page_day['right'],
                            'index_page'   => $start_page,
                        ], $data_template);
                        $contents   = view("svg{$sub_blade}.day_day", $data)->render();
                        $svgArray[] = $contents;
                    }
                } else {
                    if ($count_bp <= $day_head_sheet_max_row) {
                        $start_page++;
                        $data = array_merge([
                            'arr_weeks'    => $remain_week,
                            'arr_days'     => $arr_days,
                            'index_page'   => $start_page,
                        ], $data_template);
                        $contents   = view("svg{$sub_blade}.week_day", $data)->render();
                        $svgArray[] = $contents;
                    } else {
                        $days = $this->buildForHalfDays($arr_days, $mode);
                        $start_page++;
                        $data = array_merge([
                            'arr_weeks'    => $remain_week,
                            'arr_days'     => $days['right'][0]['right'],
                            'index_page'   => $start_page,
                        ], $data_template);
                        $contents   = view("svg{$sub_blade}.week_day", $data)->render();
                        $svgArray[] = $contents;

                        // remain days
                        $days = $days['days'];

                        foreach ($days as $day_idx => $page_day) {
                            $start_page++;
                            $data = array_merge([
                                'left_days'    => $page_day['left'],
                                'right_days'   => $page_day['right'],
                                'index_page'   => $start_page,
                            ], $data_template);
                            $contents   = view("svg{$sub_blade}.day_day", $data)->render();
                            $svgArray[] = $contents;
                        }
                    }
                }
            }
        }
        return $svgArray;
    }

    /**
     * How many day in data
     * @param $arr_days: data group by date
     * @return int
     */
    private function countBPTime($arr_days) {
        $count_bp = 0;
        foreach ($arr_days as $index_day => $day) {
            $count_bp += (count($day['measures']) == 0) ? 1 : count($day['measures']);
        }
        return $count_bp;
    }

    /**
     * Calculate how many page to enough to display data in pdf
     * @param $count_weeks: number week
     * @param $count_bp_days: number date
     * @param string $mode: mode pdf A|B
     * @return float|int
     */
    private function caclPage($count_weeks, $count_bp_days, $mode = 'A'){
        $page = 0;
        $week_sheet_max_row = 23;
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        $day_head_sheet_max_row = 14;
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        $day_sheet_max_row = 20;
        
        if($count_weeks <= $week_sheet_max_row && $count_bp_days <= $day_head_sheet_max_row ){
            $page = 1;
        } else {
            if ($count_weeks > $week_sheet_max_row && $count_weeks <= $week_sheet_max_row) {
                $page++;
                $page += (int)(ceil($count_bp_days/($day_sheet_max_row + $day_head_sheet_max_row)));
            } else {
                $full_sheet_time_bp = $day_sheet_max_row + $day_head_sheet_max_row;

                $count_sheet_week = (int)(floor($count_weeks/$week_sheet_max_row));
                $count_remain_week = $count_weeks%$week_sheet_max_row;

                if ($count_sheet_week % 2 == 0 && $count_sheet_week > 0) {
                    $page += $count_sheet_week/2;
                }

                if ($count_remain_week == 0 && $count_sheet_week % 2 == 0 && $count_sheet_week > 0) {
                    $page += (int)(ceil($count_bp_days/$full_sheet_time_bp));
                } else {
                    if ($count_bp_days <= $day_head_sheet_max_row) {
                        $page++;
                    } else {
                        $page++;
                        $page += (int)(ceil(($count_bp_days - $day_head_sheet_max_row)/($day_sheet_max_row + $day_head_sheet_max_row)));
                    }
                }
            }
        }
        return $page;
    }

    /**
     * Group data by week
     * @param $data_vitals: vital data
     * @param $avg_days_data: data after calculate average
     * @return array
     */
    private function buildWeek($data_vitals, $avg_days_data){
        $params = $this->params;
        $lang        = $params['lang'];
        $app_lang    = \Config::get('constants.' . $lang);
        $date_from   = date('Y-m-d H:i:00', strtotime($params['start_date_time']));
        $date_to     = date('Y-m-d H:i:59', strtotime($params['end_date_time']));
        $date_from_p = date('Y-m-d', strtotime($date_from));
        $date_to_p   = date('Y-m-d', strtotime($date_to));
        $date_to_e   = date('Y-m-d', strtotime('+1 days', strtotime($date_to)));
        $arr_weeks       = [];
        $cnt             = 0;
        $avg_week        = [];
        $last_day        = null;
        // calculate value pressure per date
        $calculateAverage = function ($array, $type = 'mor', $key = 'systolic') {
            if (empty($array[$type])) {
                return '-';
            }
            $array = $array[$type];
            $sum   = count($array);
            $r     = [];
            foreach ($array as $v) {
                $r[] = $v[$key];
            }
            return round(array_sum($r) / $sum);
        };

        // todo should delete? not use
        $avg2Values = function ($number1 = '', $number2 = '') {
            if (is_numeric($number1) && is_numeric($number2)) {
                return round(($number1 + $number2) / 2);
            } else if (is_numeric($number1)) {
                return $number1;
            } else if (is_numeric($number2)) {
                return $number2;
            } else {
                return '-';
            }
        };

        $count_day   = function ($start_d, $end_d) {
            $date1 = new \DateTime($start_d);
            $date2 = new \DateTime($end_d);
            return (int) $date2->diff($date1)->format("%a") + 1;
        };
        $total_days = $count_day($date_from_p, $date_to_p);

        while ($date_from_p <= $date_to_e) {
            $arr_day_done = ($date_from_p > $date_to_p);
            // column week
            if (($cnt && $cnt % 7 == 0) || $arr_day_done) {
                if ($arr_day_done) {
                    $num_day_else = intval($cnt % 7);
                    $num_day_else = $num_day_else ? $num_day_else : 7;
                    $start_w      = date('Y-m-d', strtotime("-{$num_day_else} days", strtotime($date_to_e)));
                } else {
                    $start_w = date('Y-m-d', strtotime('-7 days', strtotime($date_from_p)));
                }
                // text date from - to 1 week show each column

                $d_start_x = date('Y-m-d', strtotime($start_w));
                $d_end_x   = date('Y-m-d', strtotime('-1 days', strtotime($date_from_p)));

                $d_start_w = date($app_lang['PDF_WEEK_FORMAT'], strtotime($d_start_x));
                $d_end_w   = date($app_lang['PDF_WEEK_FORMAT'], strtotime($d_end_x));

                $a_mor_sys   = $calculateAverage($avg_week, 'mor', 'systolic');
                $a_mor_dia   = $calculateAverage($avg_week, 'mor', 'diastolic');
                $a_mor_pulse = $calculateAverage($avg_week, 'mor', 'pulse');
                $a_eve_sys   = $calculateAverage($avg_week, 'eve', 'systolic');
                $a_eve_dia   = $calculateAverage($avg_week, 'eve', 'diastolic');
                $a_eve_pulse = $calculateAverage($avg_week, 'eve', 'pulse');

                $a_sys      = $calculateAverage($avg_week, 'average', 'systolic');
                $a_dia      = $calculateAverage($avg_week, 'average', 'diastolic');
                $a_pulse    = $calculateAverage($avg_week, 'average', 'pulse');

                $riskColorsMor = $this->getRiskColorAndRiskIcon((int) $a_mor_sys, (int) $a_mor_dia);
                $riskColorsEve = $this->getRiskColorAndRiskIcon((int) $a_eve_sys, (int) $a_eve_dia);
                $riskColorsDay = $this->getRiskColorAndRiskIcon((int) $a_sys, (int) $a_dia);
                $month = '';
                if (!$last_day || date('Y-m', strtotime($last_day)) != date('Y-m', strtotime($start_w))) {
                    //$month = date($lang == 'JA' ? 'n月' : $app_lang['PDF_ONLY_MONTH'], strtotime($d_start_x));
                    // 改造BP_APP_DEV-1059 2019.09.12 VinhHP ---->
                    $month = $app_lang['PDF_MONTH'][ date('n', strtotime($d_start_x)) -1 ];
                    // 改造BP_APP_DEV-1124 2019.09.16 VinhHP ---->
                    $month = ($lang == 'JA' ? date('n', strtotime($d_start_x))."/" : $month);
                    // BP_APP_DEV-1124 2019.09.16 <----
                    // BP_APP_DEV-1059 2019.09.12 <----

                }

                $last_day = $start_w;
                $arr_weeks[] = [
                    'display_1w' => $d_start_w . $app_lang['PDF_DATE_SEPARATE'] . $d_end_w,
                    'start_date' => $d_start_x,
                    'end_date' => $d_end_x,
                    'month'    => $month,
                    'start_day' => date($app_lang['PDF_ONLY_DAY'], strtotime($d_start_x)),

                    // 改造BP_APP_DEV-2194 2022.07.22 TrungNT ---->
                    'start_day_week' => $lang == 'VI' ? $app_lang['PDF_DATES_SHORTER'][date('N', strtotime($d_start_x)) - 1] : $app_lang['PDF_DATES'][date('N', strtotime($d_start_x)) - 1],
                    // 改造BP_APP_DEV-2194 2022.07.22 <----
                    'end_day' => date($app_lang['PDF_ONLY_DAY'], strtotime($d_end_x)),
                    // 改造BP_APP_DEV-2194 2022.07.22 TrungNT ---->
                    'end_day_week' => $lang == 'VI' ? $app_lang['PDF_DATES_SHORTER'][date('N', strtotime($d_end_x)) - 1] : $app_lang['PDF_DATES'][date('N', strtotime($d_end_x)) - 1],
                    // 改造BP_APP_DEV-2194 2022.07.22 <----
                    'date_separate' => $app_lang['PDF_DATE_SEPARATE'],
                    'vitals'     => [
                        'mor' => [
                            'systolic'            => $a_mor_sys,
                            'diastolic'           => $a_mor_dia,
                            'pulse'               => $a_mor_pulse,
                            'risk_icon_systolic'  => $a_mor_sys != '-' ? $riskColorsMor['risk_icon_systolic'] : '-',
                            'risk_icon_diastolic' => $a_mor_dia != '-' ? $riskColorsMor['risk_icon_diastolic'] : '-',
                        ],
                        'eve' => [
                            'systolic'  => $a_eve_sys,
                            'diastolic' => $a_eve_dia,
                            'pulse'     => $a_eve_pulse,
                            'risk_icon_systolic'  => $a_eve_sys != '-' ? $riskColorsEve['risk_icon_systolic'] : '-',
                            'risk_icon_diastolic' => $a_eve_dia != '-' ? $riskColorsEve['risk_icon_diastolic'] : '-',
                        ],
                        'day' => [
                            'systolic'  => $a_sys,
                            'diastolic' => $a_dia,
                            'pulse'     => $a_pulse,
                            'risk_icon_systolic'  => $a_sys != '-' ? $riskColorsDay['risk_icon_systolic'] : '-',
                            'risk_icon_diastolic' => $a_dia != '-' ? $riskColorsDay['risk_icon_diastolic'] : '-',
                        ]
                    ],
                ];
                $avg_week                          = [];
                if ($total_days <= 7) {
                    break;
                }
            }
            // Data week: if total days <7 in first loop OR loop 7 days/week
            if (!empty($data_vitals[$date_from_p])) {
                $vt = $data_vitals[$date_from_p];
                !empty($vt['mor']) && ($avg_week['mor'][] = $vt['mor']);
                !empty($vt['eve']) && ($avg_week['eve'][] = $vt['eve']);
            }

            if (!empty($avg_days_data[$date_from_p])) {
                $vt = $avg_days_data[$date_from_p];
                !empty($vt['average']) && ($avg_week['average'][] = $vt['average']);
            }

            $cnt++;
            $date_from_p = date('Y-m-d', strtotime('+1 days', strtotime($date_from_p)));
        };
        return $arr_weeks;
    }

    /**
     * @param $array_days: data group by date
     * @param string $mode: mode pdf A|B
     * @return array
     */
    private function buildForHalfDays($array_days, $mode = 'A') {
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        $right_sheet_half = 14;
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        $days = [];
        $page = 0;
        $count_right = 0;
        $remains = [];
        foreach ($array_days as $day_index => $day) {
            if (array_key_exists($page, $days)) {
                $count_right = $days[$page]['right'] ? $this->countBPTime($days[$page]['right']) : 0;
            } else {
                $days[$page] = [
                    'left' => [],
                    'right' => [],
                ];
                $count_right = 0;
            }
            if ($count_right < $right_sheet_half) {
                $count_bp = (count($day['measures']) == 0 ? 1 : count($day['measures']));
                if (($count_right + $count_bp) <= $right_sheet_half) {
                    $count_right += $count_bp;
                    $days[$page]['right'][] = $day;
                } else {
                    $add_right_day = $day;
                    $add_right_day['measures'] = array_slice($day['measures'], 0, $right_sheet_half - $count_right);
                    $days[$page]['right'][] = $add_right_day;
                    $count_right += count($add_right_day['measures']);

                    $remain_bps = array_slice($day['measures'], count($add_right_day['measures']), count($day['measures']) - count($add_right_day['measures']));
                    if(count($remain_bps) == 0){
                        continue;
                    }
                    $remain = $day;
                    $remain['measures'] = $remain_bps;
                    $remains[] = $remain;
                }
            } else {
                $remains[] = $day;
            }
        }
        return ['right' => $days, 'days' => $this->buildDays($remains, $mode)];
    }

    private function buildDays($array_days, $mode = 'A') {
        // 改造BP_APP_DEV-4288 2025.02.11 LoiNV ---->
        $left_sheet = 14;
        // 改造BP_APP_DEV-4288 2025.02.11 <----
        $right_sheet = 20;
        $days = [];
        $page = 0;
        $count_right = 0;
        $count_left = 0;
        foreach ($array_days as $day_index => $day) {
            if (array_key_exists($page, $days)) {
                $count_left = $days[$page]['left'] ? $this->countBPTime($days[$page]['left'])  : 0;
                $count_right = $days[$page]['right'] ? $this->countBPTime($days[$page]['right']) : 0;
            } else {
                $days[$page] = [
                    'left' => [],
                    'right' => [],
                ];
                $count_left = 0;
                $count_right = 0;
            }

            if ($count_left < $left_sheet) {
                $count_bp = count($day['measures']) == 0 ? 1 : count($day['measures']);
                if (($count_bp + $count_left) <= $left_sheet) {
                    $count_left += $count_bp;
                    $days[$page]['left'][] = $day;
                } else {
                    $add_left_day = $day;
                    $add_left_day['measures'] = array_slice($day['measures'], 0, $left_sheet - $count_left);
                    $days[$page]['left'][] = $add_left_day;
                    $count_left += count($add_left_day['measures']);

                    $add_right_day = $day;
                    $add_right_day['measures'] = array_slice($day['measures'], count($add_left_day['measures']), $right_sheet);
                    $days[$page]['right'][] = $add_right_day;
                    $count_right += count($add_right_day['measures']);

                    $remain_bps = array_slice($day['measures'], count($add_left_day['measures']) + count($add_right_day['measures']), count($day['measures']) - (count($add_left_day['measures']) + count($add_right_day['measures'])));

                    if(count($remain_bps) == 0){
                        if($count_right == $right_sheet){
                            $page++;
                        }
                        continue;
                    }
                    $remain_bps = array_chunk($remain_bps, $left_sheet + $right_sheet);
                    foreach($remain_bps as $page_day_ixd => $bp_page){
                        $left_bp = array_slice($bp_page, 0, $left_sheet);
                        $right_bp = array_slice($bp_page, $left_sheet, $right_sheet);

                        $left_day = $day;
                        $left_day['measures'] = $left_bp;

                        $right_day = $day;
                        $right_day['measures'] = $right_bp;

                        $page++;
                        $days[$page] = [
                            'left' => [$left_day],
                            'right' => count($right_bp) != 0 ? [$right_day] : [],
                        ];
                    }
                }
            } else if ($count_right < $right_sheet) {
                $count_bp = count($day['measures']) == 0 ? 1 : count($day['measures']);
                if (($count_bp + $count_right) <= $right_sheet) {
                    $count_right += $count_bp;
                    $days[$page]['right'][] = $day;
                } else {
                    $add_right_day = $day;
                    $add_right_day['measures'] = array_slice($day['measures'], 0, $right_sheet - $count_right);
                    $days[$page]['right'][] = $add_right_day;
                    $count_right += count($add_right_day['measures']);
                    $remain_bps = array_slice($day['measures'], count($add_right_day['measures']), count($day['measures']) - count($add_right_day['measures']));
                    $remain_bps = array_chunk($remain_bps, $left_sheet + $right_sheet);
                    foreach($remain_bps as $page_day_ixd => $bp_page){
                        $left_bp = array_slice($bp_page, 0, $left_sheet);
                        $right_bp = array_slice($bp_page, $left_sheet, $right_sheet);

                        $left_day = $day;
                        $left_day['measures'] = $left_bp;

                        $right_day = $day;
                        $right_day['measures'] = $right_bp;

                        $page++;
                        $days[$page] = [
                            'left' => [$left_day],
                            'right' => count($right_bp) != 0 ? [$right_day] : [],
                        ];
                    }
                }
            } else {
                $remain_bps = array_chunk($day['measures'], $left_sheet + $right_sheet);
                if(count($day['measures']) == 0){
                    $left_day = $day;
                    $left_day['measures'] = [];
                    $page++;
                        $days[$page] = [
                            'left' => [$left_day],
                            'right' => [],
                        ];
                }else{
                    foreach($remain_bps as $page_day_ixd => $bp_page){
                        $left_bp = array_slice($bp_page, 0, $left_sheet);
                        $right_bp = array_slice($bp_page, $left_sheet, $right_sheet);

                        $left_day = $day;
                        $left_day['measures'] = $left_bp;

                        $right_day = $day;
                        $right_day['measures'] = $right_bp;

                        $page++;
                        $days[$page] = [
                            'left' => [$left_day],
                            'right' => count($right_bp) != 0 ? [$right_day] : [],
                        ];
                    }
                }
                // BP_APP_DEV-1059 2018.08.23 <----
            }
        }
        return $days;
    }

    /**
     * getDataVitalInternal from api get_vitals_data
     * @param  array $header header to call api internal project
     * @param  array $params params to call api internal project
     * @return object        object vitals data
     */
    private function getDataVitalInternal($header, $params)
    {
        if (!empty($params['date_to_data'])){
            $params['end_date_time'] = date('YmdHi', strtotime($params['date_to_data']));
        }
        $memoC   = new MemosController;
        $request = request()->duplicate();
        $request->headers->add($header);
        $request->replace($params);
        $request->request->add($params);

        $data     = $memoC->get_vital_data($request);
        $data_api = $data->getContent();

        $json_data = (object) null;
        try {
            $json_data = json_decode($data_api, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                // error invalid json data
                $this->errCode = 'ERROR_NO_DATA';
            } elseif (empty($json_data['success']) && isset($json_data['message']['access_token'])) {
                // WRONG_TOKEN
                $this->message = $json_data['message'];
            }
        } catch (\Exception $e) {
            // error decode data fail
            $this->errCode = 'ERROR_NO_DATA';
        }
        return $json_data;
    }

    /**
     * dataVitalByDate filter data and order its by date
     * @param  array  $header header call api
     * @param  array  $params param call api
     * @return array
     */
    public function dataVitalByDate($header, $params)
    {
        // call api
        $raw_data = $this->getDataVitalInternal($header, $params);
        // dd($raw_data);
        // FILTER DATA BY DATE: sys, dia, pulse, date
        if ($raw_data && (!empty($raw_data['vitals']))) {
            $arr_day  = [];
            $vitals   = empty($raw_data['vitals']) ? [] : $raw_data['vitals'];
            $memo     = empty($raw_data['memo']) ? [] : $raw_data['memo'];
            $has_data = false;

            // 改造BP_APP_DEV-1060 2019.06.28 TranHV ---->
            $setting = $this->settingsModel->getSetting($this->userSetting->user_id);
            $start_mor = $setting['bp_morning_start'].':00';
            $end_mor   = $setting['bp_morning_end'].':59';
            $start_eve = $setting['bp_evening_start'].':00';
            $end_eve   = $setting['bp_evening_end'].':59';
            // BP_APP_DEV-1060 2019.06.28 <----

            // 改造BP_APP_DEV-4229 2024.12.19 LoiNV ---->
            $invalid_bp = function ($i) {
                return !isset($i['systolic']) || !is_numeric($i['systolic']) 
                    || !isset($i['diastolic']) || !is_numeric($i['diastolic']) 
                    || !isset($i['pulse']) || !is_numeric($i['pulse']) 
                    || intval($i['systolic']) < intval($i['diastolic']) 
                    || intval($i['systolic']) < 0 || intval($i['diastolic']) < 0 || intval($i['pulse']) < 0;
            };
            // 改造BP_APP_DEV-4229 2024.12.19 <----

            // filter data in morning and evening
            foreach ($vitals as $k => $i) {
                // 改造BP_APP_DEV-4229 2024.12.19 LoiNV ---->
                // If invalid data, skip this data
                // else, do nothing
                if($invalid_bp($i)){
                    continue;
                }
                else{
                    // Do nothing
                }
                // 改造BP_APP_DEV-4229 2024.12.19 <----
                
                $measure_date = $i['measure_date'];
                $measure_date = substr($measure_date, 0, 19);

                // 改造BP_APP_DEV-1060 2019.08.22 TranHV ---->
                $date         = date('Y-m-d', strtotime($measure_date));
                //get data by hour
                $measure_date_hour = substr($measure_date, 0, 13);
                $arr_day[$date]['hour'][$measure_date_hour][] = [
                    'measure_date' => $measure_date,
                    'systolic'     => $i['systolic'],
                    'diastolic'    => $i['diastolic'],
                    'pulse'        => $i['pulse'],
                    // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                    'afib_mode'         => $i['afib_mode'],
                    'error_ha_night'    => $i['error_ha_night'],
                    // BP_APP_DEV-1107 2019.11.15 <----
                ];
                //end get data by hour
                // Calc $m_or_e = 'mor' or 'eve' belong to $date
                $itemDate             = (new Carbon($measure_date));
                $time                 = clone $itemDate;
                //Moring
                $morStart             = (new Carbon($time->format('Y-m-d ') . $start_mor))->format('Y-m-d H:i:s');
                $morEnd               = (new Carbon($time->format('Y-m-d ') . $end_mor))->format('Y-m-d H:i:s');
                $morPreStart          = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $start_mor;
                $morPreEnd            = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $end_mor;
                $morNexStart          = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_mor;
                $morNexEnd            = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_mor;
                //Evening
                $eveStart             = (new Carbon($time->format('Y-m-d ') . $start_eve))->format('Y-m-d H:i:s');
                $eveEnd               = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                $evePreStart          = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                $evePreEnd            = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                $eveNexStart          = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                $eveNexEnd            = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;

                // ReCalculation time range
                if($start_mor >= $end_mor){ // End moring next day
                    $morEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_mor;
                    $morPreEnd  = (new Carbon($time->format('Y-m-d ') . $end_mor))->format('Y-m-d H:i:s');
                    $morNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_mor;

                    $eveStart   = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                    $eveEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                    $evePreStart= (new Carbon($time->format('Y-m-d ') . $start_eve))->format('Y-m-d H:i:s');
                    $evePreEnd  = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                    $eveNexStart= date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                    $eveNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                }else{ // Morning same day
                    if($start_eve >= $end_eve){ // End evening next day
                        $eveEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                        $evePreEnd  = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                        $eveNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                    }else{
                        if($start_eve <= $end_mor){ //
                            $eveStart   = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                            $eveEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                            $evePreStart= (new Carbon($time->format('Y-m-d ') . $start_eve))->format('Y-m-d H:i:s');
                            $evePreEnd  = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                            $eveNexStart= date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                            $eveNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                        }
                    }
                }
                $msDate               = $itemDate->format('Y-m-d');
                $preMsDate            = date('Y-m-d', strtotime('-1 day', strtotime($msDate)));
                $nexMsDate            = date('Y-m-d', strtotime('+1 day', strtotime($msDate)));

                $m_or_e = '';
                // Check for today
                if($morStart <= $itemDate && $itemDate <= $morEnd){
                    $date = $itemDate->format('Y-m-d');
                    $m_or_e = 'mor';
                } else if($eveStart <= $itemDate && $itemDate <= $eveEnd){
                    $date = $itemDate->format('Y-m-d');
                    $m_or_e = 'eve';
                }// Check for yesterday
                else if($morPreStart <= $itemDate && $itemDate <= $morPreEnd){
                    $date = $preMsDate;
                    $m_or_e = 'mor';
                }else if($evePreStart <= $itemDate && $itemDate <= $evePreEnd){
                    $date = $preMsDate;
                    $m_or_e = 'eve';
                }// Check for tomorrow
                else if($morNexStart <= $itemDate && $itemDate <= $morNexEnd){
                    $date = $nexMsDate;
                    $m_or_e = 'mor';
                }else if($eveNexStart <= $itemDate && $itemDate <= $eveNexEnd){
                    $date = $nexMsDate;
                    $m_or_e = 'eve';
                }

                $has_data = true;

                $isNightError = false;
                $checkModeEvening = checkModeEvening($i['afib_mode']);
                if($i['error_ha_night'] != 0 && $i['error_ha_night'] != 99 && $i['error_ha_night'] != null){
                    $isNightError = true;
                }
                if($checkModeEvening || $isNightError){
                    $m_or_e = '';
                }
                // echo "<pre style=color:red>";
                // print_r($arr_day);
                // echo "</pre>";
                $arr_day[$date][$m_or_e][] = [
                    'measure_date' => $measure_date,
                    'systolic'     => $i['systolic'],
                    'diastolic'    => $i['diastolic'],
                    'pulse'        => $i['pulse'],
                    // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                    'afib_mode'         => $i['afib_mode'],
                    'error_ha_night'    => $i['error_ha_night'],
                    // BP_APP_DEV-1107 2019.11.15 <----
                ];
                rsort($arr_day[$date][$m_or_e]); // sort date decrease
                krsort($arr_day[$date]); // mor first, eve after
            }
            // average data in morning and evening
            // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
            $arr_day = $this->avgItem($arr_day, $params['user_id']);
            // BP_APP_DEV-1059 2018.08.23 <----
            // parse memos for notes
            $med_per_day = $this->userSetting->medicine_per_day;
            // dd($memo);
            foreach ($memo as $date => $i) {
                if (empty($arr_day[$date])) {
                    $arr_day[$date] = [];
                }
                // dd(2);
                if ($med_per_day) {
                    //Get medicines time. E.g $med_per_day = 2, get only medicine_time1, medicine_time2 don't care medicine_time3
                    $arr_med = array_slice([$i['medicine_time1'], $i['medicine_time2'], $i['medicine_time3']], 0, $med_per_day);
                    // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                    $arr_day[$date]['medicines'] = $arr_med;
                    // BP_APP_DEV-1059 2018.08.23 <----
                    //Use array_filter with purpose: If any element in array [$arr_med] is NULL then will be DELETE
                    $count_med             = count(array_filter($arr_med));
                    $arr_day[$date]['med'] = $count_med ? ($count_med . '/' . $med_per_day) : '';
                } else {
                    $arr_day[$date]['med'] = '';
                }
                $memo_text = !empty($i['memo_text']) ? $i['memo_text'] : '';
                // 改造BP_APP_DEV-1149 2020.02.24 AnhNVT ---->
                $arr_day[$date]['note'] = $memo_text;
                // 改造BP_APP_DEV-1149 2020.02.24 <----
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                $memoMask = !empty($i['memo_mask']) ? $i['memo_mask'] : null;
                $memoMaskArr = $this->parseMemoMask($memoMask);
                $arr_day[$date]['memo_mask'] = (!empty($memoMaskArr['hospital']) && $memoMaskArr['hospital'] == true) ? true : false;
                // BP_APP_DEV-1059 2018.08.23 <----
            }
            ksort($arr_day);

            if ($has_data == false) {
                $this->errCode = 'ERROR_NO_DATA';
            }
            $res = ['arr_day' => $arr_day];
        } else {
            $this->errCode = 'ERROR_NO_DATA';
            $res           = ['arr_day' => []];
        }
        return $res;
    }
    // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
    /**
     * allDataVitalByDate filter data and order its by date
     * @param  array  $header header call api
     * @param  array  $params param call api
     * @return array
     */
    public function allDataVitalByDate()
    {
        $header = $this->header;
        $params = $this->params;
        $lang        = $params['lang'];
        $app_lang    = \Config::get('constants.' . $lang);
        $mode        = 'A';
        // call api
        $raw_data = $this->getDataVitalInternal($header, $params);
        // FILTER DATA BY DATE: sys, dia, pulse, date
        if ($raw_data && (!empty($raw_data['vitals']))) {
            $arr_day  = [];
            $vitals   = empty($raw_data['vitals']) ? [] : $raw_data['vitals'];
            $memo     = empty($raw_data['memo']) ? [] : $raw_data['memo'];
            $arr_day_belong = [];

            // 改造BP_APP_DEV-1060 2019.06.28 TranHV ---->
            $setting = $this->settingsModel->getSetting($this->userSetting->user_id);
            $start_mor = $setting['bp_morning_start'].':00';
            $end_mor   = $setting['bp_morning_end'].':59';
            $start_eve = $setting['bp_evening_start'].':00';
            $end_eve   = $setting['bp_evening_end'].':59';
            // BP_APP_DEV-1060 2019.06.28 <----

            $invalid_bp = function ($i) {
                // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                // Change condition to check invalid bp data
                // Check systolic, diastolic, pulse diff null and they are not less than 0
                // and systolic is not less than diastolic
                // 改造BP_APP_DEV-4229 2024.12.09 LoiNV ---->
                return !isset($i['systolic']) || !is_numeric($i['systolic']) 
                    || !isset($i['diastolic']) || !is_numeric($i['diastolic']) 
                    || !isset($i['pulse']) || !is_numeric($i['pulse']) 
                    || intval($i['systolic']) < intval($i['diastolic']) 
                    || intval($i['systolic']) < 0 || intval($i['diastolic']) < 0 || intval($i['pulse']) < 0;
                // 改造BP_APP_DEV-4229 2024.12.09 <----
                // 改造BP_APP_DEV-2760 2024.05.29 <----
            };
            // filter data in morning and evening
            foreach ($vitals as $k => $i) {
                $measure_date = $i['measure_date'];
                $measure_date = substr($measure_date, 0, 19);
                $date         = date('Y-m-d', strtotime($measure_date));
                $belong_date  = date('Y-m-d', strtotime($measure_date));
                $date_string  = date($app_lang['PDF_DAY_FORMAT'], strtotime($measure_date));
                $time         = date('G:i', strtotime($measure_date));

                $isNightError = false;
                $checkModeEvening = checkModeEvening($i['afib_mode']);
                if($i['error_ha_night'] != 0 && $i['error_ha_night'] != 99 && $i['error_ha_night'] != null){
                    $isNightError = true;
                }

                // 改造BP_APP_DEV-4229 2024.12.19 LoiNV ---->
                $riskColors = $this->getRiskColorAndRiskIcon((int)$i['systolic'], (int)$i['diastolic'], $params['user_id']);
                
                // If invalid systolic or is night error, set no icon
                // else, do nothing
                if(!isset($i['systolic']) || !is_numeric($i['systolic']) || intval($i['systolic']) < 0 || $isNightError){
                    $riskColors['risk_icon_systolic'] = '-';
                }
                else{
                    // Do nothing
                }

                // If invalid diastolic or is night error, set no icon
                // else, do nothing
                if(!isset($i['diastolic']) || !is_numeric($i['diastolic']) || intval($i['diastolic']) < 0 || $isNightError){
                    $riskColors['risk_icon_diastolic'] = '-';
                }
                else{
                    // Do nothing
                }

                $arr_day[$date]['date_string'] = $date_string;
                //$arr_day[$date]['month']        = date($lang == 'JA' ? 'n月' : $app_lang['PDF_ONLY_MONTH'], strtotime($measure_date));
                // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
                $arr_day[$date]['month'] = $app_lang['PDF_MONTH'][ date('n', strtotime($measure_date)) -1 ];
                $arr_day[$date]['month'] = ($lang == 'JA' ? date('n', strtotime($measure_date))."/" : $arr_day[$date]['month']);
                // BP_APP_DEV-1059 2019.09.05 <----
                $arr_day[$date]['day']          = date($app_lang['PDF_ONLY_DAY'], strtotime($measure_date));
                $week_d    = date('N', strtotime($measure_date));
                // 改造BP_APP_DEV-2198 2022.07.22 TrungNT ---->
                $arr_day[$date]['day_week']     = $lang == 'VI' ? $app_lang['PDF_DATES_SHORTER'][$week_d - 1] : $app_lang['PDF_DATES'][$week_d - 1];
                // 改造BP_APP_DEV-2198 2022.07.22 <----
                $arr_day[$date]['med']     = [
                    'first'  => false,
                    'second' => false,
                    'third'  => false,
                ];
                $arr_day[$date]['note']     = '';
                $arr_day[$date]['mask']     = '';
                //Error
                $isError = false;
                if($i['tight_fit'] == "0" || $i['is_motion'] == "1"
                    || $i['hand_hight'] == "2" || $i['hand_hight'] == "3"){
                    $isError = true;
                }

                $isAFIB = false;
                // 改造BP_APP_DEV-4663 2025.05.07 HaiTN ---->
                // Possible Afib detection is now available even when measured in "Normal Measurement Mode."
                if($i['afib_detect'] && $i['afib_detect'] == 1){
                // 改造BP_APP_DEV-4663 2025.05.07 <----
                    $isAFIB = true;
                }
                // 改造BP_APP_DEV-1178 2019.12.02 TranHV ---->
                $checkModeEvening = checkModeEvening($i['afib_mode']);
                $arr_day[$date]['measures'][$measure_date] = [
                    'measure_date' => $measure_date,
                    'systolic'     => $isNightError ? '-' : $i['systolic'],
                    'diastolic'    => $isNightError ? '-' : $i['diastolic'],
                    'pulse'        => $isNightError ? '-' : $i['pulse'],
                    'time'         => $time,
                    'risk_icon_systolic'  => $riskColors['risk_icon_systolic'],
                    'risk_icon_diastolic' => $riskColors['risk_icon_diastolic'],
                    'is_error'          => $isError,
                    'is_afib'           => $isAFIB,
                    'strange_pulse'     => $i['strange_pulse'],
                    'mode'              => $i['afib_mode'],
                    'is_night_error'    => $isNightError,
                    // 改造BP_APP_DEV-4288 2025.02.04 LoiNV ---->
                    'is_irregular_pulse_interval' => boolval($i['is_irregular_pulse_interval']),
                    // 改造BP_APP_DEV-4288 2025.02.04 <----
                ];
                // BP_APP_DEV-1178 2019.11.15 <----
                // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                if($i['afib_mode'] == 1){
                }
                $mode = (in_array($i['afib_mode'], [1, 4]) || $checkModeEvening == 1 || $mode == 'B') ? 'B' : 'A';
                // BP_APP_DEV-1107 2019.11.15 <----

                // 改造BP_APP_DEV-1060 2019.08.22 TranHV ---->
                // Calc $m_or_e = 'mor' or 'eve' belong to $date
                $itemDate             = (new Carbon($measure_date));
                $time                 = clone $itemDate;
                //Moring
                $morStart             = (new Carbon($time->format('Y-m-d ') . $start_mor))->format('Y-m-d H:i:s');
                $morEnd               = (new Carbon($time->format('Y-m-d ') . $end_mor))->format('Y-m-d H:i:s');
                $morPreStart          = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $start_mor;
                $morPreEnd            = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $end_mor;
                $morNexStart          = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_mor;
                $morNexEnd            = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_mor;
                //Evening
                $eveStart             = (new Carbon($time->format('Y-m-d ') . $start_eve))->format('Y-m-d H:i:s');
                $eveEnd               = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                $evePreStart          = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                $evePreEnd            = date('Y-m-d ', strtotime('-1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                $eveNexStart          = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                $eveNexEnd            = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;

                // ReCalculation time range
                if($start_mor >= $end_mor){ // End moring next day
                    $morEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_mor;
                    $morPreEnd  = (new Carbon($time->format('Y-m-d ') . $end_mor))->format('Y-m-d H:i:s');
                    $morNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_mor;

                    $eveStart   = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                    $eveEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                    $evePreStart= (new Carbon($time->format('Y-m-d ') . $start_eve))->format('Y-m-d H:i:s');
                    $evePreEnd  = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                    $eveNexStart= date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                    $eveNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                }else{ // Morning same day
                    if($start_eve >= $end_eve){ // End evening next day
                        $eveEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                        $evePreEnd  = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                        $eveNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                    }else{
                        if($start_eve <= $end_mor){ //
                            $eveStart   = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                            $eveEnd     = date('Y-m-d ', strtotime('+1 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                            $evePreStart= (new Carbon($time->format('Y-m-d ') . $start_eve))->format('Y-m-d H:i:s');
                            $evePreEnd  = (new Carbon($time->format('Y-m-d ') . $end_eve))->format('Y-m-d H:i:s');
                            $eveNexStart= date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $start_eve;
                            $eveNexEnd  = date('Y-m-d ', strtotime('+2 days', strtotime($time->format('Y-m-d')))) . $end_eve;
                        }
                    }
                }
                $msDate               = $itemDate->format('Y-m-d');
                $preMsDate            = date('Y-m-d', strtotime('-1 day', strtotime($msDate)));
                $nexMsDate            = date('Y-m-d', strtotime('+1 day', strtotime($msDate)));

                $m_or_e = '';
                // Check for today
                if($morStart <= $itemDate && $itemDate <= $morEnd){
                    $belong_date = $msDate;
                    $m_or_e = 'mor';
                } else if($eveStart <= $itemDate && $itemDate <= $eveEnd){
                    $belong_date = $msDate;
                    $m_or_e = 'eve';
                }// Check for yesterday
                else if($morPreStart <= $itemDate && $itemDate <= $morPreEnd){
                    $belong_date = $preMsDate;
                    $m_or_e = 'mor';
                }else if($evePreStart <= $itemDate && $itemDate <= $evePreEnd){
                    $belong_date = $preMsDate;
                    $m_or_e = 'eve';
                }// Check for tomorrow
                else if($morNexStart <= $itemDate && $itemDate <= $morNexEnd){
                    $belong_date = $nexMsDate;
                    $m_or_e = 'mor';
                }else if($eveNexStart <= $itemDate && $itemDate <= $eveNexEnd){
                    $belong_date = $nexMsDate;
                    $m_or_e = 'eve';
                }
                // 改造BP_APP_DEV-1107 2019.11.18 TrungNH ---->
                $checkModeEvening = checkModeEvening($i['afib_mode']);
                
                // If valid data, add to array for checking mor, even data
                // else, do nothing 
                if (!$invalid_bp($i) && $m_or_e != '' && $checkModeEvening == 0 && !$isNightError) {
                // BP_APP_DEV-1107 2019.11.18 <----
                    $arr_day_belong[$belong_date][$m_or_e][$measure_date] = false;
                }
                else{
                    // Do nothing
                }

                $arr_day[$date]['measures'][$measure_date]['is_eve'] = false;
                $arr_day[$date]['measures'][$measure_date]['is_mor'] = false;
                $arr_day[$date]['measures'][$measure_date]['day_belong'] = $belong_date;
                // BP_APP_DEV-1060 2019.08.22 <----

                    ksort($arr_day[$date]['measures']); // sort date decrease
                    ksort($arr_day[$date]['measures']); // sort date decrease
                ksort($arr_day[$date]['measures']); // sort date decrease
                // 改造BP_APP_DEV-4229 2024.12.19 <----
            }
            dd($vitals);
            foreach ($arr_day_belong as $date => $i) {
                if (!empty($i['mor'])) {
                    ksort($i['mor']);
                    $first_mor = array_keys($i['mor'])[0];
                    $start   = strtotime($first_mor);
                    $count_mor = 0;
                    foreach ($i['mor'] as $time_mor => $j) {
                        $time = strtotime($time_mor);
                        // check count 2 data morning in 10 mins
                        if($count_mor < Config::get('constants.BP_COUNT') && (abs($time - $start) < 10 * 60)) {
                            $count_mor ++;
                            $arr_day_belong[$date]['mor'][$time_mor] = true;
                        }
                    }

                }
                if (!empty($i['eve'])) {
                    asort($i['eve']);
                    $first_eve = array_keys($i['eve'])[0];
                    $start   = strtotime($first_eve);
                    $count_eve = 0;
                    foreach ($i['eve'] as $time_eve => $j) {
                        $time = strtotime($time_eve);
                        // check count 2 data evening in 10 mins
                        if($count_eve < Config::get('constants.BP_COUNT') && (abs($time - $start) < 10 * 60)) {
                            $count_eve ++;
                            $arr_day_belong[$date]['eve'][$time_eve] = true;
                        }
                    }

                }
            }

            foreach ($arr_day as $date => $date_info) {
                //$arr_day[$date]['measures'][$measure_date]['day_belong'] = $belong_date;
                foreach ($date_info['measures'] as $messure_i => $messure) {
                    $date_belong = $messure['day_belong'];
                    if(!empty($arr_day_belong[$date_belong]) && !empty($arr_day_belong[$date_belong]['mor']) && !empty($arr_day_belong[$date_belong]['mor'][$messure_i])){
                        $arr_day[$date]['measures'][$messure_i]['is_mor'] = $arr_day_belong[$date_belong]['mor'][$messure_i];
                    }

                    if(!empty($arr_day_belong[$date_belong]) && !empty($arr_day_belong[$date_belong]['eve']) && !empty($arr_day_belong[$date_belong]['eve'][$messure_i])){
                        $arr_day[$date]['measures'][$messure_i]['is_eve'] = $arr_day_belong[$date_belong]['eve'][$messure_i];
                    }
                }
            }

            // parse memos for notes
            $med_per_day = $this->userSetting->medicine_per_day;
            foreach ($memo as $date => $i) {
                if (empty($arr_day[$date])) {
                    // 改造BP_APP_DEV-1295 2020.04.20 MinhTQ ---->
                    // Check if day is empty then skip
                    $have_medicine = false;
                    switch ($med_per_day) {
                        case 1:
                            if (!empty($i["medicine_time1"])) {
                                $have_medicine = true;
                            } else {
                                //nothing
                            }
                            break;
                        case 2:
                            if (!empty($i["medicine_time1"]) || !empty($i["medicine_time2"])) {
                                $have_medicine = true;
                            } else {
                                //nothing
                            }
                            break;
                        case 3:
                            if (!empty($i["medicine_time1"]) || !empty($i["medicine_time2"]) || !empty($i["medicine_time3"])) {
                                $have_medicine = true;
                            } else {
                                //nothing
                            }
                            break;
                        default:
                            //nothing
                    }
                    if (!$have_medicine) {
                        if ($this->checkIsMemoVsTextEmpty($i)) {
                            continue;
                        } else {
                            //nothing
                        }
                    } else {
                        //nothing
                    }

                    $arr_day[$date] = [];
                    $arr_day[$date]['measures'] = [];
                    //$arr_day[$date]['month']        = date($lang == 'JA' ? 'n月' : $app_lang['PDF_ONLY_MONTH'], strtotime($date));
                    // 改造BP_APP_DEV-1059 2019.09.05 VinhHP ---->
                    $arr_day[$date]['month'] = $app_lang['PDF_MONTH'][ date('n', strtotime($date)) -1 ];
                    $arr_day[$date]['month'] = ($lang == 'JA' ? date('n', strtotime($date))."/" : $arr_day[$date]['month']);
                    // BP_APP_DEV-1059 2019.09.05 <----
                    $arr_day[$date]['day']          = date($app_lang['PDF_ONLY_DAY'], strtotime($date));
                    $week_d    = date('N', strtotime($date));
                    // 改造BP_APP_DEV-2198 2022.07.22 TrungNT ---->
                    $arr_day[$date]['day_week']     = $lang == 'VI' ? $app_lang['PDF_DATES_SHORTER'][$week_d - 1] : $app_lang['PDF_DATES'][$week_d - 1];
                    // 改造BP_APP_DEV-2198 2022.07.22 <----
                    $arr_day[$date]['date_string']  = date($app_lang['PDF_DAY_FORMAT'], strtotime($date));
                }
                if ($med_per_day) {
					          $arr_day[$date]['med'] = [
                        'first'  => $i['medicine_time1'] ? true : false,
                        'second' => $i['medicine_time2'] ? true : false,
                        'third'  => $i['medicine_time3'] ? true : false,
                    ];
				        } else {
                    $arr_day[$date]['med'] = [
                        'first'  => false,
                        'second' => false,
                        'third'  => false,
                    ];
                }
                $memo_text              = !empty($i['memo_text']) ? $i['memo_text'] : '';
                $memo_mask              = !empty($i['memo_mask']) ? $i['memo_mask'] : '';
                // 改造BP_APP_DEV-1149 2020.02.24 AnhNVT ---->
                $arr_day[$date]['note'] = $memo_text;
                // BP_APP_DEV-1149 2020.02.24 AnhNVT <----
                $arr_day[$date]['mask'] = $memo_mask;
                $arr_day[$date]['mask_values'] = $this->parseMemoMask($memo_mask, $memo_text);
            }
            ksort($arr_day);

            if (count($arr_day) == 0) {
                $this->errCode = 'ERROR_NO_DATA';
            }
            $res = [
                'arr_day'   => $arr_day,
                'mode' => $mode,
            ];
        } else {
            $this->errCode = 'ERROR_NO_DATA';
            $res = [
                'arr_day'   => [],
                'mode' => '',
            ];
        }
        return $res;
    }
    // BP_APP_DEV-1059 2018.08.23 <----

    /**
     * Check is have any action in dairy.
     * @param $memo
     * @return bool
     */
    private function checkIsMemoVsTextEmpty($memo)
    {
        if (empty($memo['memo_text'])) {
            $b_memo_mask = substr(MeasureUtils::convertBinary($memo['memo_mask']), 0, 14);
            if ($b_memo_mask == "10000000000000") {
                return true;
            }
        } else {
            //nothing
        }
        return false;
    }

  	/**
  	 * Average maximum 2 elements within 10 minutes from first item.
  	 * @param $arr_day
  	 * @param $userId
  	 * @return     array   BP mor and eve.
  	 */
    // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
    private function avgItem($arr_day, $userId)
    // BP_APP_DEV-1059 2018.08.23 <----
    {
        $dataReturn = [];
        foreach ($arr_day as $date_i => $vitals) {
            $dataReturn[$date_i] = [];

            if (!empty($vitals['mor'])) {
                $morning = array_reverse($vitals['mor']);
                $len     = count($morning) <= Config::get('constants.BP_COUNT') ? count($morning) : Config::get('constants.BP_COUNT');
                // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                $countMorning = 0;
                // BP_APP_DEV-1107 2019.11.15 <----

                $mSystolic       = 0;
                $mDiastolic      = 0;
                $mPulse          = 0;
                $mCountSystolic  = 0;
                $mCountDiastolic = 0;
                $mCountPulse     = 0;

                foreach ($morning as $i => $item) {
                    // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                    $checkModeEvening = checkModeEvening($item['afib_mode']);
                    $isNightError = false;
                    if($item['error_ha_night'] != 0 && $item['error_ha_night'] != 99 && $item['error_ha_night'] != null){
                        $isNightError = true;
                    }
                    if ($checkModeEvening == 1 || $isNightError)
                        continue;
                    if ($countMorning >= $len)
                        break;
                    if ($countMorning == 0)
                        $start   = strtotime($item['measure_date']);
                    $countMorning++;
                    // BP_APP_DEV-1107 2019.11.15 <----
                    $itemTime = strtotime($item['measure_date']);
                    if (abs($itemTime - $start) < 10 * 60) {
                        // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                        // Check systolic is existed then count systolic and calculate the total systolic 
                        if (isset($item['systolic']) && is_numeric($item['systolic'])) {
                            $mSystolic += (int) $item['systolic'];
                            $mCountSystolic++;
                        } else {
                            // nothing
                        }

                        // Check diastolic is existed then count diasotlic and calculate the total diastolic 
                        if (isset($item['diastolic']) && is_numeric($item['diastolic'])) {
                            $mDiastolic += (int) $item['diastolic'];
                            $mCountDiastolic++;
                        } else {
                            // nothing
                        }

                        // Check pulse is existed then count pulse and calculate the total pulse 
                        if (isset($item['pulse']) && is_numeric($item['pulse'])) {
                            $mPulse += (int) $item['pulse'];
                            $mCountPulse++;
                        } else {
                            // nothing
                        }
                        // 改造BP_APP_DEV-2760 2024.05.29 <----
                    }
                }
                $a_sys   = ($mCountSystolic != 0) ? round($mSystolic / $mCountSystolic) : 0;
                $a_dia   = ($mCountDiastolic != 0) ? round($mDiastolic / $mCountDiastolic) : 0;
                $a_pulse = ($mCountPulse != 0) ? round($mPulse / $mCountPulse) : 0;
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                $riskColors = $this->getRiskColorAndRiskIcon((int)$a_sys, (int)$a_dia, $userId);
                
                // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                // Check count of systolic, diastolic, is valid
                // 改造BP_APP_DEV-1107 2019.11.18 TrungNH ---->
                if ($mCountSystolic > 0 && $mCountDiastolic > 0) {
                    // BP_APP_DEV-1107 2019.11.18 <----
                        $dataReturn[$date_i]['mor'] = array_merge(
                            [
                                'hhii'      => date('G:i', $start),
                                'systolic'  => (int) $a_sys,
                                'diastolic' => (int) $a_dia,
                                'pulse'     => (int) $a_pulse
                            ],
                            $riskColors
                        );
                } else {
                    // nothing
                }
                // 改造BP_APP_DEV-2760 2024.05.29 <----
                // BP_APP_DEV-1059 2018.08.23 <----
            }
            if (!empty($vitals['eve'])) {
                $evening = $vitals['eve'];
                $len     = count($evening) <= Config::get('constants.BP_COUNT') ? count($evening) : Config::get('constants.BP_COUNT');
                // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                $countEvening = 0;
                // BP_APP_DEV-1107 2019.11.15 <----

                $eSystolic       = 0;
                $eDiastolic      = 0;
                $ePulse          = 0;
                $eCountSystolic  = 0;
                $eCountDiastolic = 0;
                $eCountPulse     = 0;

                foreach ($evening as $i => $item) {
                    // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
                    $checkModeEvening = checkModeEvening($item['afib_mode']);
                    $isNightError = false;
                    if($item['error_ha_night'] != 0 && $item['error_ha_night'] != 99 && $item['error_ha_night'] != null){
                        $isNightError = true;
                    }
                    if ($checkModeEvening == 1 || $isNightError)
                        continue;
                    if ($countEvening >= $len) {
                        break;
                    }
                    if ($countEvening == 0)
                        $end   = strtotime($item['measure_date']);
                    $countEvening++;
                    // BP_APP_DEV-1107 2019.11.15 <----

                    $itemTime = strtotime($item['measure_date']);
                    if (abs($end - $itemTime) < 10 * 60) {
                        // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                        // Check systolic is existed then count systolic and calculate the total systolic 
                         if (isset($item['systolic']) && is_numeric($item['systolic'])) {
                            $eSystolic += (int) $item['systolic'];
                            $eCountSystolic++;
                        } else {
                            // nothing
                        }

                        // Check diastolic is existed then count diastolic and calculate the total diastolic 
                        if (isset($item['diastolic']) && is_numeric($item['diastolic'])) {
                            $eDiastolic += (int) $item['diastolic'];
                            $eCountDiastolic++;
                        } else {
                            // nothing
                        }

                        // Check pulse is existed then count pulse and calculate the total pulse 
                        if (isset($item['pulse']) && is_numeric($item['pulse'])) {
                            $ePulse += (int) $item['pulse'];
                            $eCountPulse++;
                        } else {
                            // nothing
                        }
                        // 改造BP_APP_DEV-2760 2024.05.29 <----
                    }
                }
                $a_dia   = ($eCountDiastolic != 0) ? round($eDiastolic / $eCountDiastolic) : 0;
                $a_pulse = ($eCountPulse != 0) ? round($ePulse / $eCountPulse) : 0;
                $a_sys   = ($eCountSystolic != 0) ? round($eSystolic / $eCountSystolic) : 0;
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                $riskColors = $this->getRiskColorAndRiskIcon((int)$a_sys, (int)$a_dia, $userId);
                
                // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                // Check count of systolic, diastolic, is valid
                // 改造BP_APP_DEV-1107 2019.11.18 TrungNH ---->
                if ($eCountDiastolic > 0 && $eCountSystolic > 0) {
                // BP_APP_DEV-1107 2019.11.18 <----
                    $dataReturn[$date_i]['eve'] = array_merge(
                        [
                            'hhii'      => date('G:i', $end),
                            'systolic'  => (int) $a_sys,
                            'diastolic' => (int) $a_dia,
                            'pulse'     => (int) $a_pulse
                        ],
                        $riskColors
                    );
                } else {
                    // nothing
                }
                // 改造BP_APP_DEV-2760 2024.05.29 <----
            }

            // average by day (24h)
            if (!empty($vitals['hour'])) {
                $avg_diastolic = 0;
                $avg_systolic = 0;
                $avg_pulse = 0;
                $count = 0;
                foreach ( $vitals['hour'] as $hour_i => $hour){
                    $avg_hour = $this->getAvg($hour);

                    // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                    // Check the avg_diastolic, avg_systolic, avg_pulse is valid
                    if (!isset($avg_hour['avg_diastolic'])
                        || !isset($avg_hour['avg_systolic']) || !isset($avg_hour['avg_pulse'])) {
                            continue;
                    } else {
                        // nothing
                    }
                    // 改造BP_APP_DEV-2760 2024.05.29 <----
                    
                    $avg_diastolic  += $avg_hour['avg_diastolic'];
                    $avg_systolic   += $avg_hour['avg_systolic'];
                    $avg_pulse      += $avg_hour['avg_pulse'];
                    $count++;
                }
                // 改造BP_APP_DEV-2760 2024.06.04 LoiNV ---->
                $avg_diastolic_date     = !$count ? null : (int) round($avg_diastolic / $count);
                $avg_systolic_date      = !$count ? null : (int) round($avg_systolic / $count);
                $avg_pulse_date         = !$count ? null : (int) round($avg_pulse / $count);
                // 改造BP_APP_DEV-2760 2024.06.04 <----

                $riskColors = $this->getRiskColorAndRiskIcon($avg_systolic_date, $avg_diastolic_date, $userId);

                // 改造BP_APP_DEV-1107 2019.11.18 TrungNH ---->
                
                // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
                // Check count avg is valid
                if ($count > 0) {
                // BP_APP_DEV-1107 2019.11.18 <----
                    $dataReturn[$date_i]['average'] = array_merge([
                        'diastolic' => $avg_diastolic_date,
                        'systolic'  => $avg_systolic_date,
                        'pulse'     => $avg_pulse_date], $riskColors);
                } else {
                    // nothing
                }
                // 改造BP_APP_DEV-2760 2024.05.29 <----
                
            }
        }
        // BP_APP_DEV-1059 2018.08.23 <----
        return $dataReturn;
    }

    public function getAvg($arrays){
        // remove items mor or eve invalid
        $invalid_bp = function ($i) {
            // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
            // Change condition to check invalid bp data
            // Check systolic, diastolic, pulse diff null and they are not less than 0
            // and systolic is not less than diastolic
            // 改造BP_APP_DEV-4229 2024.12.09 LoiNV ---->
            return !isset($i['systolic']) || !is_numeric($i['systolic']) 
            || !isset($i['diastolic']) || !is_numeric($i['diastolic']) 
            || !isset($i['pulse']) || !is_numeric($i['pulse']) 
            || intval($i['systolic']) < intval($i['diastolic']) 
            || intval($i['systolic']) < 0 || intval($i['diastolic']) < 0 || intval($i['pulse']) < 0;
            // 改造BP_APP_DEV-4229 2024.12.09 <----
            // 改造BP_APP_DEV-2760 2024.05.29 <----
        };
        $diastolic = 0;
        $systolic = 0;
        $pulse = 0;
        $count = 0;
        foreach ($arrays as $bp_i => $bp){
            // 改造BP_APP_DEV-1107 2019.11.15 TrungNH ---->
            $checkModeEvening = checkModeEvening($bp['afib_mode']);
            $isNightError = false;
            if($bp['error_ha_night'] != 0 && $bp['error_ha_night'] != 99 && $bp['error_ha_night'] != null){
                $isNightError = true;
            }
            if ($checkModeEvening == 1 || $isNightError)
                continue;
            // BP_APP_DEV-1107 2019.11.15 <----
            if (!$invalid_bp($bp)) {
                $diastolic   += $bp['diastolic'];
                $systolic   += $bp['systolic'];
                $pulse      += $bp['pulse'];
                $count++;
            }
        }
        // 改造BP_APP_DEV-2760 2024.06.04 LoiNV ---->
        $avg_diastolic  = !$count ? null : (int) round($diastolic / $count);
        $avg_systolic   = !$count ? null : (int) round($systolic / $count);
        $avg_pulse      = !$count ? null : (int) round($pulse / $count);
        // 改造BP_APP_DEV-2760 2024.06.04 <----
        return [
            'avg_diastolic'  => $avg_diastolic,
            'avg_systolic'   => $avg_systolic,
            'avg_pulse'      => $avg_pulse,
        ];
    }

    /**
     * getMorEveMaxMinValue Get min max value from morning and evening data serve for level BP[mmHg] in SVG graph
     * @param  array $arr_days: data of days was separated into each page
     * @return array: Value min, max, array label level
     */
    public function getMorEveMaxMinValue($arr_days)
    {
        $systolic  = [];
        $diastolic = [];

        // 改造BP_APP_DEV-2760 2024.06.04 LoiNV ---->
        // Loop over each page
        foreach($arr_days as $index_page => $days){
            // Loop over each day
            foreach ($days as $index_day => $day) {
                // If morning systolic has value, get it
                // else, do nothing
                if(isset($day['vitals']['mor']['systolic'])){
                    $systolic[] = $day['vitals']['mor']['systolic'];
                }
                else{
                    // Do nothing
                }

                // If evening systolic has value, get it
                // else, do nothing
                if(isset($day['vitals']['eve']['systolic'])){
                    $systolic[] = $day['vitals']['eve']['systolic'];
                }
                else{
                    // Do nothing
                }

                // If morning diastolic has value, get it
                // else, do nothing
                if(isset($day['vitals']['mor']['diastolic'])){
                    $diastolic[] = $day['vitals']['mor']['diastolic'];
                }
                else{
                    // Do nothing
                }

                // If evening diastolic has value, get it
                // else, do nothing
                if(isset($day['vitals']['eve']['diastolic'])){
                    $diastolic[] = $day['vitals']['eve']['diastolic'];
                }
                else{
                    // Do nothing
                }
            }
        }
        // 改造BP_APP_DEV-2760 2024.06.04 <----

        // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
        // Check if $systolic is empty or $diastolic is empty 
        // if true, set $maxSYS to null and set $minDIA to null
        // else find the maximum value in $systolic and find the minimum value in $diastolic
        if(empty($systolic) || empty($diastolic)){
            $maxSYS = null;
            $minDIA = null;
        } else {
            $maxSYS = max($systolic);
            $minDIA = min($diastolic);
        }

        $itemPerfect = $this->createLevelLabel($maxSYS, $minDIA,$this->userSetting->systolic_target,$this->userSetting->diastolic_target);
        return $itemPerfect;
        // 改造BP_APP_DEV-2760 2024.05.29 <----
    }

    /**
     * getAvgMaxMinValue Get min max value from average data serve for level BP[mmHg] in SVG graph
     * @param  array $arr_days: data of days was separated into each page
     * @return array: Value min, max, array label level
     */
    public function getAvgMaxMinValue($arr_days)
    {
        $systolic  = [];
        $diastolic = [];

        // 改造BP_APP_DEV-2760 2024.06.04 LoiNV ---->
        // Loop over each page
        foreach($arr_days as $index_page => $days){
            // Loop over each day
            foreach ($days as $index_day => $day) {
                // If average systolic has value, get it
                // else, do nothing
                if(isset($day['vitals']['average']['systolic'])){
                    $systolic[] = $day['vitals']['average']['systolic'];
                }
                else{
                    // Do nothing
                }

                // If average diastolic has value, get it
                // else, do nothing
                if(isset($day['vitals']['average']['diastolic'])){
                    $diastolic[] = $day['vitals']['average']['diastolic'];
                }
                else{
                    // Do nothing
                }
            }
        }
        // 改造BP_APP_DEV-2760 2024.06.04 <----

        // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
        // Check if $systolic is empty or $diastolic is empty 
        // if true, set $maxSYS to null and set $minDIA to null
        // else find the maximum value in $systolic and find the minimum value in $diastolic
        if(empty($systolic) || empty($diastolic)){
            $maxSYS = null;
            $minDIA = null;
        } else {
            $maxSYS = max($systolic);
            $minDIA = min($diastolic);
        }

        // Call the createLevelLabel method with the calculated $maxSYS, $minDIA, and user targets for systolic and diastolic
        $arrLevelLabel = $this->createLevelLabel($maxSYS, $minDIA, $this->userSetting->systolic_target, $this->userSetting->diastolic_target);

        // Return the resulting array from createLevelLabel
        return $arrLevelLabel;
        // 改造BP_APP_DEV-2760 2024.05.29 <----
    }

    // 改造BP_APP_DEV-2760 2024.05.29 LoiNV ---->
    /**
    *
    * @param int|null $sys: Max Sys Data
    * @param int|null $dia: Min Dia Data
    * @param int|null $targetSYS: Target Sys Data
    * @param int|null $targetDIA: Target Dia Data
    * @return array array level label including min, max, numbers of level label
    */
    private function createLevelLabel($sys, $dia, $targetSYS, $targetDIA) {
        // Default value of min diastolic and max systolic
        $minDIADefault = 60;
        $maxSYSDefault = 180;

        // Calculate maximum systolic and minimum diastolic values considering targets
        $maxSYS = max($sys, $targetSYS) ?? $maxSYSDefault;

        // Check if $dia is null
        if (is_null($dia)) {
            // If $dia is null, set $minDIA to either 60 or $targetDIA based on whether $targetDIA is null or not
            $minDIA = is_null($targetDIA) ? $minDIADefault : $targetDIA;
        } else {
            // If $dia is not null
            // Set $minDIA to either $dia or the minimum value between $dia and $targetDIA based on whether $targetDIA is null or not
            $minDIA = is_null($targetDIA) ? $dia : min($dia, $targetDIA);
        }


        // Check if the difference between $maxSYS and $minDIA is less than or equal to 10
        if (abs($maxSYS - $minDIA) <= 10) {
            // Get average of min and max
            $minMaxAverage = round(($minDIA + $maxSYS)/2);

            // If minMaxAverage is less than 6, set minDIA is 0 and maxSYS is 12 to ensure 3 lines as calculation
            // else, scale with minDIA and maxSYS with range is 12 to ensure 3 line as calculation
            if($minMaxAverage < 6) {
                $minDIA = 0;
                $maxSYS = 12;
            } else {
                $minDIA = $minMaxAverage - 6;
                $maxSYS = $minMaxAverage + 6;
            }
        } else {
            // nothing
        }

        // Initialize variables and arrays for level suggestions
        $listSuggest = [];
        $ceilMaxSys = ceil($maxSYS / 10) * 10;
        $floorMinDia = floor($minDIA / 10) * 10;
        $multiples = 10;
        $minLine = 3;
        $maxLine = 9;
        // Generate level suggestions based on a loop
        for ($i = 2; $i <= 11; $i++){
            $newBlock = ceil(($ceilMaxSys - $floorMinDia) / ($i-1) / $multiples) * $multiples;
            $blockCreated = ($ceilMaxSys - $floorMinDia) >= 2 * $multiples && $newBlock > 0 ? $newBlock : $multiples;
            $startCreated = floor($floorMinDia / $blockCreated);
            $endCreated = ceil($ceilMaxSys / $blockCreated);
            $totalLine = $minLine > ($endCreated - $startCreated + 1) || $maxLine < ($endCreated - $startCreated + 1) ? 0 : $endCreated - $startCreated + 1;
            
            // Add level suggestion to the list
            $listSuggest[] = [
                'min' => $floorMinDia,
                'step' => $totalLine - 1,
                'space' => $blockCreated
            ];
        }

        // Find the item with the maximum step in the list of suggestions
        $itemPerfect = array_reduce($listSuggest, function ($maxStepItem, $currentItem) {
            return $currentItem['step'] > $maxStepItem['step'] ? $currentItem : $maxStepItem;
        }, $listSuggest[0]);
        

        // Initialize an array to store ticks, this array store as a STACK
        $arrTicks = [];

        // Sequence add tick to first index in array
        array_unshift($arrTicks, intval($minDIA - $itemPerfect['space']));

        for ($i = 0; $i <= $itemPerfect['step']; $i++) {
            array_unshift($arrTicks, intval($itemPerfect['min'] + $itemPerfect['space'] * $i));
        }
        
        // Check there are any redudant block with greater than $ceilMaxSys with 1 space
        // If yes, then reupdating $max Tick as upper border with $maxSYS + space
        // else, then add upper border with $maxSYS + space
        if($arrTicks[0] - $maxSYS >= $itemPerfect['space']) {
            $arrTicks[0] = intval($maxSYS + $itemPerfect['space']);
        } else {
            array_unshift($arrTicks, intval($maxSYS + $itemPerfect['space']));
        }
        

        return ['min' => end($arrTicks), 'max' => $arrTicks[0], 'valueLabel' => $arrTicks];
    }
    // 改造BP_APP_DEV-2760 2024.05.29 <----

    /**
     * Svg2Pdf Convert svg to pdf file
     * @param array $svgs array multiple string svg content
     * @return string String path pdf to send mail
     */
    public function Svg2Pdf($svgs)
    {
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        ini_set("pcre.backtrack_limit", "10000000");
        // BP_APP_DEV-1059 2018.08.23 <----
        if (empty($svgs)) {
            return null;
        }
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
        $fontDirs      = (array) $defaultConfig['fontDir'];
        // BP_APP_DEV-1059 2018.08.23 <----
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData          = $defaultFontConfig['fontdata'];

        $mode = 'utf-8';
        $font_lang = $this->params['font_lang'];
        $default_font = $font_lang[0];

        try {
            $mPdf = new Mpdf([
                'mode'         => $mode,
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                'format'       => 'A4',
                // BP_APP_DEV-1059 2018.08.23 <----
                'orientation'  => 'L',
                'fontDir'      => array_merge($fontDirs, [
                    public_path('/fonts'),
                ]),
                'fontdata'     => $fontData + [
                    $font_lang[0] => [
                        'R' => $font_lang[1],
                        'I' => $font_lang[1],
                        'B' => $font_lang[1],
                    ],
                ],
                // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
                'default_font' => $default_font,
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0
            ]);
            $mPdf->pdf_version = '1.5';
            $mPdf->img_dpi = '72';
            // BP_APP_DEV-1059 2018.08.23 <----
            $mPdf->SetAutoPageBreak(true, 0);
            $fileName = "OMRON Graph Report_". mt_rand() . ".pdf"; //microtime(true)
            $pdfPath  = public_path() . '/tmp/' . $fileName;
            foreach ($svgs as $key => $svg) {
                if (!$key) {
                    $mPdf->AddPage();
                }
                $mPdf->WriteHTML($svg);
            }
            $mPdf->Output($pdfPath, 'F');
        } catch (\Mpdf\MpdfException $e) {
            // check error of corrupt PDF file
            \Log::info('BP_LOGBOOK_PDF_ERROR: '. json_encode($e->getMessage()));
            $pdfPath = '';
        }
        return $pdfPath;
    }

    // --- MEASURE DATA TO CSV ---
    /**
     * measureToStringCsv Convert data measure to csv string
     * @return null|string String file name or exception with null return
     */
    public function measureToStringCsv()
    {
        // concat data to string
        $csv_arr_str   = [];
        $csv_arr_str[] = \Config::get('constants.' . $this->params['lang'] . '.PDF_TITLE_CSV');
        $raw_data      = $this->getDataVitalInternal($this->header, $this->params);
        if ($raw_data && !empty($raw_data['vitals'])) {
            $vitals = $raw_data['vitals'];
            $vitals = !empty($vitals) ? array_reverse($vitals) : $vitals;
            foreach ($vitals as $arr) {
                // 改造BP_APP_DEV-1178 2019.12.02 TranHV ---->
                $isNightError = false;
                if($arr['error_ha_night'] != 0 && $arr['error_ha_night'] != 99 && $arr['error_ha_night'] != null){
                    $isNightError = true;
                }

                $measure_date  = substr($arr['measure_date'], 0, 19);
                // 改造BP_APP_DEV-1060 2019.08.22 TranHV ---->
                $measure_date  = date('Y-m-d G:i', strtotime($measure_date));
                // BP_APP_DEV-1060 2019.08.22 <----
                $timeZone      = $arr['timeZone'];
                $systolic   = $isNightError ? '--' : $arr['systolic'];
                $diastolic  = $isNightError ? '--' : $arr['diastolic'];
                $pulse      = $isNightError ? '--' : $arr['pulse'];
                // 改造BP_APP_DEV-1222 2020.04.17 TranHV ---->
                // Change parameter because function change in php 7
                $csv_arr_str[] = join(',', [$measure_date, $timeZone, $systolic, $diastolic, $pulse, $arr['deviceModel']]);
                // BP_APP_DEV-1222 2020.04.17 <----
                // BP_APP_DEV-1178 2019.11.15 <----
            }
            if (count($csv_arr_str) > 1) {
                // put to file csv
                // 改造BP_APP_DEV-1222 2020.04.17 TranHV ---->
                // Change parameter because function change in php 7
                $string_csv = join("\n", $csv_arr_str);
                // BP_APP_DEV-1222 2020.04.17 <----
                $newName    = date('YmdHis', time()) . mt_rand() . '.csv';
                $output     = public_path() . '/tmp/' . $newName;

                $file = fopen($output, 'wb');
                fputs($file, "\xEF\xBB\xBF");
                fwrite($file, $string_csv);
                fclose($file);
            }
        } else {
            $this->errCode = 'ERROR_NO_DATA';
        }
        return (!empty($output) && file_exists($output)) ? $output : null;
    }

  // 改造BP_APP_DEV-1059 2019.08.23 TranHV ---->
	/**
	 * Get risk level icon and color
	 *
	 * @param $sys
	 * @param $dia
	 * @param null $userId
	 * @return array
	 */
    public function getRiskColorAndRiskIcon($sys, $dia, $userId = null)
    {
        $userId = $userId ?? $this->userSetting->user_id;
        $riskColors = MeasureUtils::checkColor((int)$sys, (int)$dia, $userId);
        $arrayRiskColors = [
            "risk_color_total" => $riskColors['total'],
            "risk_color_systolic" => $riskColors['systolic'],
            "risk_color_diastolic" => $riskColors['diastolic'],
        ];
        $arrayRiskIcons = [
            "risk_icon_systolic" => MeasureUtils::getIconByColorRisk($riskColors['systolic']),
            "risk_icon_diastolic" => MeasureUtils::getIconByColorRisk($riskColors['diastolic']),
        ];

        return array_merge($arrayRiskColors, $arrayRiskIcons);
    }

    public function parseMemoMask($memo_mask, $memo_text = ''){
        $memoOrder = new MemoOrdersModel;
        $memoOrderUser = $memoOrder->getByUserId($this->userSetting->user_id);
        $app_memo_id = [
            'medication_1' => 0, 'medication_2' => 1, 'medication_3' => 2, 'un_smoking' => 3, 'smoking' => 4,
            'un_drink' => 5, 'drink' => 6, 'un_salt' => 7, 'salt' => 8, 'veggie' => 9, 'un_veggie' => 10,
            'sleep' => 11, 'un_sleep' => 12, 'exercise' => 13, 'un_exercise' => 14, 'hospital' => 15,
            'message' => 16
        ];
        $app_memo_name = [
            0 => 'medication_1', 1 => 'medication_2', 2 => 'medication_3', 3 => 'un_smoking', 4 => 'smoking',
            5 => 'un_drink', 6 => 'drink', 7 => 'un_salt', 8 => 'salt', 9 => 'veggie', 10 => 'un_veggie',
            11 => 'sleep', 12 => 'un_sleep', 13 => 'exercise', 14 => 'un_exercise', 15 => 'hospital',
            16 => 'message'
        ];
        $order = [];
        for ($i = 0; $i < count($memoOrderUser); $i++) {
            $order[$memoOrderUser[$i] - 1] = $app_memo_name[$i];
        }

        $memo_check = decbin($memo_mask);
        $memo_arr = [];
        for ($i = 0; $i < count($memoOrderUser); $i++) {
            $id = $app_memo_id[$order[$i]];
            if ($order[$i] == 'message') {
                $memo_arr[$order[$i]] = $memo_text;
            } else if (substr($memo_check, 16 - $id, 1) == 1) {
                $memo_arr[$order[$i]] = $memo_mask !== 0 ? (substr($memo_check, 16-$id, 1) == '1' ? true : false) : false;
            }
        }

        return $memo_arr;
    }
    // BP_APP_DEV-1059 2018.08.23 <----
}
