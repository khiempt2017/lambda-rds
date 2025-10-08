/**
 * Created by KhiemPT <khiempt@vitalify.asia> on 2025/03/08
 * Copyright (c) 2025 Vitalify Asia Co.,Ltd. All rights reserved.
 */
import moment from 'moment-timezone';
import { BP_COUNT, DATE_FORMAT, NUMERIC, DEFAULT_SYS, DEFAULT_DIA, TIME, ERROR_CODES, DAYS, MEASURE, BP, MEASURE_PARAMS } from '/opt/constants/common';
import { LoggerService} from '/opt/services';
interface VitalItem {
  measure_date: string;
  systolic: number;
  diastolic: number;
  pulse: number;
  error_ha_night: number;
  afib_mode?: number;
}

interface AppSettings {
  morning_time: {
    start_time_full: string;
    end_time_full: string;
  };
  evening_time: {
    start_time_full: string;
    end_time_full: string;
  };
  sys?: number;  // Add sys setting
  dia?: number;  // Add dia setting
}

interface ParsedData {
  evening?: string;
  morning?: string;
  morning_pulse?: string;
  evening_pulse?: string;
  morning_color?: string;
  evening_color?: string;
  count?: number;
}

interface PressureMorEveResult {
  dataReturn: { [key: string]: any };
  dataCount: { [key: string]: number };
}

export class MeasureUtils {

  private static RISK_COLOR = {
    level_0: '#ecedf0',
    level_1: '#005F9E',
    level_2: '#D1406D',
    level_3: '#9b245a',
    level_4: '#4f2437',
  };

  /**
   * Parses and processes vital data to generate a structured report for a specified date range.
   * 
   * @param type - The type of report to generate, such as 'night'.
   * @param dateCheckFrom - The starting date for the report in string format.
   * @param data - The input data object containing vitals information.
   * @param settings - AppSettings object containing configurations for morning and evening time ranges.
   * @returns An object mapping dates to their corresponding parsed data, including vitals and risk levels.
   */

  public static parseData(type: string, dateCheckFrom: string, data, settings): { [key: string]: ParsedData } {
    const dataReturn: { [key: string]: ParsedData } = {};
    const start = moment(dateCheckFrom);
    const vitas = data?.vitals ?? [];
    const dataAfter = this.parsePressureMorEve(vitas, settings);
    LoggerService.defaultWriteLog(`dataAfter: ${JSON.stringify(dataAfter, null, 2)}`);
    const dataCount = dataAfter.dataCount || {};
    const dataParse = dataAfter.dataReturn || {};
    const numDays = BP.NUM_DAYS;

    for (let nx = 0; nx < numDays; nx++) {
      const date = moment(start).add(nx, 'days').format(DATE_FORMAT.YYYYMMDD);
      dataReturn[date] = {};

      if (dataParse[date]) {
        dataParse[date].count = dataCount[date] || BP.DEFAULT_COUNT;
        dataReturn[date] = { ...dataReturn[date], ...dataParse[date] };
      } else {
        dataReturn[date] = {
          evening: '--/--',
          morning: '--/--',
          morning_pulse: '--',
          evening_pulse: '--',
          morning_color: this.RISK_COLOR.level_0,
          evening_color: this.RISK_COLOR.level_0,
          count: dataCount[date] || BP.DEFAULT_COUNT,
        };
      }
    }
    LoggerService.defaultWriteLog(`dataReturn: ${JSON.stringify(dataReturn)}`);
    return dataReturn;
  }

  /**
    * This function takes a collection of vital signs and a settings object as input. It processes the vital signs and
    * generates a structured report for a specified date range. The report includes the morning and evening blood pressure
    * and pulse values, as well as the corresponding risk levels.
    * @param vitas - An array of vital sign objects.
    * @param settings - An object containing settings for morning and evening time ranges.
    * @returns An object mapping dates to their corresponding parsed data, including vitals and risk levels.
  */
  private static parsePressureMorEve(vitas: VitalItem[], settings): PressureMorEveResult {
    const dateCollection: { [key: string]: any } = {};
    const dataCount: { [key: string]: number } = {};
    
    const start_mor = settings.morning_time.start_time_full;
    const end_mor = settings.morning_time.end_time_full;
    const start_eve = settings.evening_time.start_time_full;
    const end_eve = settings.evening_time.end_time_full;

    for (const item of vitas) {
        const itemDate = moment(item.measure_date.substring(0, TIME.STRING_LENGTH_19));
        const msDateTime = itemDate.format(DATE_FORMAT.YYYYMMDD_HHmmss);
        const time = itemDate.clone();
        let mor_eve_over_flg = false;

        let morStart = `${time.format(DATE_FORMAT.YYYYMMDD)} ${start_mor}`;
        let morEnd = `${time.format(DATE_FORMAT.YYYYMMDD)} ${end_mor}`;
        let morPreStart = `${moment(time).subtract(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_mor}`;
        let morPreEnd = `${moment(time).subtract(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_mor}`;
        let morNexStart = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_mor}`;
        let morNexEnd = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_mor}`;

        let eveStart = `${time.format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
        let eveEnd = `${time.format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
        let evePreStart = `${moment(time).subtract(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
        let evePreEnd = `${moment(time).subtract(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
        let eveNexStart = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
        let eveNexEnd = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;

        if (start_mor >= end_mor) {
            mor_eve_over_flg = true;
            morEnd = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_mor}`;
            morPreEnd = `${time.format(DATE_FORMAT.YYYYMMDD)} ${end_mor}`;
            morNexEnd = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_mor}`;

            eveStart = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
            eveEnd = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
            evePreStart = `${time.format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
            evePreEnd = `${time.format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
            eveNexStart = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
            eveNexEnd = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
        } else {
            if (start_eve >= end_eve) {
                eveEnd = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
                evePreEnd = `${time.format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
                eveNexEnd = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
                mor_eve_over_flg = true;
            } else if (start_eve <= end_mor) {
                mor_eve_over_flg = true;
                eveStart = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
                eveEnd = `${moment(time).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
                evePreStart = `${time.format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
                evePreEnd = `${time.format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
                eveNexStart = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${start_eve}`;
                eveNexEnd = `${moment(time).add(DAYS.TWO, 'days').format(DATE_FORMAT.YYYYMMDD)} ${end_eve}`;
            }
        }

        const msDate = itemDate.format(DATE_FORMAT.YYYYMMDD);
        const preMsDate = moment(msDate).subtract(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD);
        const nexMsDate = moment(msDate).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD);

        dataCount[msDate] = dataCount[msDate] || BP.DEFAULT_COUNT;
        dataCount[preMsDate] = dataCount[preMsDate] || BP.DEFAULT_COUNT;
        dataCount[nexMsDate] = dataCount[nexMsDate] || BP.DEFAULT_COUNT;

        const isNightError = item.error_ha_night !== ERROR_CODES.NIGHT_ERROR_DEFAULT && 
                           item.error_ha_night !== ERROR_CODES.NIGHT_ERROR_SPECIAL && 
                           item.error_ha_night !== null;

        if (!isNightError) {
            if (msDateTime <= evePreEnd) {
                const dt = mor_eve_over_flg ? 
                    moment(evePreEnd).subtract(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD) :
                    moment(evePreEnd).format(DATE_FORMAT.YYYYMMDD);
                dataCount[dt] = (dataCount[dt] || BP.DEFAULT_COUNT) + NUMERIC.ONE;
            } else if (moment(msDateTime).isAfter(evePreEnd) && msDateTime <= eveEnd) {
                const dt = mor_eve_over_flg ?
                    moment(evePreEnd).format(DATE_FORMAT.YYYYMMDD) :
                    moment(msDateTime).format(DATE_FORMAT.YYYYMMDD);
                dataCount[dt] = (dataCount[dt] || BP.DEFAULT_COUNT) + NUMERIC.ONE;
            } else if (moment(msDateTime).isAfter(eveEnd) && msDateTime <= eveNexEnd) {
                const dt = mor_eve_over_flg ?
                    moment(msDateTime).format(DATE_FORMAT.YYYYMMDD) :
                    moment(eveNexEnd).format(DATE_FORMAT.YYYYMMDD);
                dataCount[dt] = (dataCount[dt] || BP.DEFAULT_COUNT) + NUMERIC.ONE;
            } else {
                const dt = mor_eve_over_flg ?
                    moment(eveNexEnd).format(DATE_FORMAT.YYYYMMDD) :
                    moment(eveNexEnd).add(NUMERIC.ONE, 'days').format(DATE_FORMAT.YYYYMMDD);
                dataCount[dt] = (dataCount[dt] || BP.DEFAULT_COUNT) + NUMERIC.ONE;
            }
        }

        if (moment(itemDate).isBetween(moment(morStart), moment(morEnd), undefined, '[]')) {
            const dateStr = itemDate.format(DATE_FORMAT.YYYYMMDD);
            if (!dateCollection[dateStr]) dateCollection[dateStr] = {};
            if (!dateCollection[dateStr].morning) dateCollection[dateStr].morning = [];
            dateCollection[dateStr].morning.push(item);
        }
        if (moment(itemDate).isBetween(moment(eveStart), moment(eveEnd), undefined, '[]')) {
            const dateStr = itemDate.format(DATE_FORMAT.YYYYMMDD);
            if (!dateCollection[dateStr]) dateCollection[dateStr] = {};
            if (!dateCollection[dateStr].evening) dateCollection[dateStr].evening = [];
            dateCollection[dateStr].evening.push(item); 
        }

        // Check for yesterday
        if (moment(itemDate).isBetween(moment(morPreStart), moment(morPreEnd), undefined, '[]')) {
            const dateStr = preMsDate;
            if (!dateCollection[dateStr]) dateCollection[dateStr] = {};
            if (!dateCollection[dateStr].morning) dateCollection[dateStr].morning = [];
            dateCollection[dateStr].morning.push(item);
        }
        if (moment(itemDate).isBetween(moment(evePreStart), moment(evePreEnd), undefined, '[]')) {
            const dateStr = preMsDate;
            if (!dateCollection[dateStr]) dateCollection[dateStr] = {};
            if (!dateCollection[dateStr].evening) dateCollection[dateStr].evening = [];
            dateCollection[dateStr].evening.push(item);
        }

        // Check for tomorrow  
        if (moment(itemDate).isBetween(moment(morNexStart), moment(morNexEnd), undefined, '[]')) {
            const dateStr = nexMsDate;
            if (!dateCollection[dateStr]) dateCollection[dateStr] = {};
            if (!dateCollection[dateStr].morning) dateCollection[dateStr].morning = [];
            dateCollection[dateStr].morning.push(item);
        }
        if (moment(itemDate).isBetween(moment(eveNexStart), moment(eveNexEnd), undefined, '[]')) {
            const dateStr = nexMsDate; 
            if (!dateCollection[dateStr]) dateCollection[dateStr] = {};
            if (!dateCollection[dateStr].evening) dateCollection[dateStr].evening = [];
            dateCollection[dateStr].evening.push(item);
        }
    }

    // 改造BPD-135 2025.09.23 KhiemPT ---->
    // Check if blood pressure is invalid
    const invalidBp = (i): boolean => {
      const hasInvalidField = 
        i.systolic === undefined || isNaN(Number(i.systolic)) ||
        i.diastolic === undefined || isNaN(Number(i.diastolic)) ||
        i.pulse === undefined || isNaN(Number(i.pulse));
    
      const systolic = Number(i.systolic);
      const diastolic = Number(i.diastolic);
      const pulse = Number(i.pulse);
    
      return hasInvalidField ||
        systolic < diastolic ||
        systolic < 0 ||
        diastolic < 0 ||
        pulse < 0;
    };
    // BPD-135 2025.09.23 KhiemPT <----

    const dataReturn: { [key: string]: any } = {};
    for (const [dateStr, valueDate] of Object.entries(dateCollection)) {
        const vitals = valueDate;
        dataReturn[dateStr] = {};
        if (vitals.morning?.length) {
            const morning = vitals.morning.reverse();
            const len = Math.min(morning.length, BP_COUNT);
            let start: moment.Moment | null = null;
            let countMorning = BP.INITIAL_VALUES.COUNT;

            let mSystolic = BP.INITIAL_VALUES.SYSTOLIC;
            let mDiastolic = BP.INITIAL_VALUES.DIASTOLIC;
            let mPulse = BP.INITIAL_VALUES.PULSE;
            let mCountSystolic = BP.INITIAL_VALUES.COUNT_SYSTOLIC;
            let mCountDiastolic = BP.INITIAL_VALUES.COUNT_DIASTOLIC;
            let mCountPulse = BP.INITIAL_VALUES.COUNT_PULSE;
            for (const item of morning) {
                // 改造BPD-135 2025.09.23 KhiemPT ---->
                if (invalidBp(item)) {
                  // If blood pressure is invalid, skip the item
                  LoggerService.defaultWriteLog(`invalidBp morning: ${item}, ${dateStr}`);
                  continue;
                } else {
                  // Do nothing
                }
                // BPD-135 2025.09.23 KhiemPT <----
                const checkModeEvening = this.checkModeEvening(item.afib_mode);
                const isNightError = item.error_ha_night !== ERROR_CODES.NIGHT_ERROR_DEFAULT && 
                                   item.error_ha_night !== ERROR_CODES.NIGHT_ERROR_SPECIAL && 
                                   item.error_ha_night !== null;

                if (checkModeEvening || isNightError) continue;
                if (countMorning >= len) break;

                if (countMorning === 0) {
                    const measureDate = item.measure_date.substring(0, MEASURE.TIME_WINDOW.SUBSTRING_LENGTH);
                    start = moment(measureDate);
                }
                countMorning++;

                const measureDate = item.measure_date.substring(0, MEASURE.TIME_WINDOW.SUBSTRING_LENGTH);
                const itemTime = moment(measureDate);
                // 改造BPD-135 2025.09.23 KhiemPT ---->
                if (start && Math.abs(start.diff(itemTime, 'seconds')) < MEASURE.TIME_WINDOW.MINUTES * NUMERIC.SIXTY) {
                  if (!isNaN(Number(item.systolic))) {
                    // BPD-135 2025.09.23 KhiemPT <----
                        mSystolic += Number(item.systolic);
                        mCountSystolic++;
                  }
                  // 改造BPD-135 2025.09.23 KhiemPT ---->
                  if (!isNaN(Number(item.diastolic))) {
                      mDiastolic += Number(item.diastolic);
                      mCountDiastolic++;
                  }
                  if (!isNaN(Number(item.pulse))) {
                      mPulse += Number(item.pulse);
                      mCountPulse++;
                  }
                  // BPD-135 2025.09.23 KhiemPT <----
                }
            }
            const ma = mCountDiastolic ? Math.round(mDiastolic / mCountDiastolic) : '--';
            const mc = mCountPulse ? Math.round(mPulse / mCountPulse) : '--';
            const mb = mCountSystolic ? Math.round(mSystolic / mCountSystolic) : '--';
            
            const color = this.checkColor(mb, ma, settings);
            
            dataReturn[dateStr].morning = `${mb}/${ma}`;
            dataReturn[dateStr].morning_pulse = mc;
            dataReturn[dateStr].morning_color = color.total;
            dataReturn[dateStr].morning_systolic_color = color.systolic; // Note: This logic doesn't use from anywhere
            dataReturn[dateStr].morning_diastolic_color = color.diastolic; // Note: This logic doesn't use from anywhere
            dataReturn[dateStr].morning_moment = start; //Note: This logic doesn't use from anywhere
        } else {
            dataReturn[dateStr].morning = '--/--';
            dataReturn[dateStr].morning_pulse = '--';
            dataReturn[dateStr].morning_color = this.RISK_COLOR.level_0;
        }

        if (vitals.evening?.length) {
            const evening = vitals.evening;
            const len = Math.min(evening.length, BP_COUNT);
            let end: moment.Moment | null = null;
            let countEvening = BP.INITIAL_VALUES.COUNT;

            let eSystolic = BP.INITIAL_VALUES.SYSTOLIC;
            let eDiastolic = BP.INITIAL_VALUES.DIASTOLIC;
            let ePulse = BP.INITIAL_VALUES.PULSE;
            let eCountSystolic = BP.INITIAL_VALUES.COUNT_SYSTOLIC;
            let eCountDiastolic = BP.INITIAL_VALUES.COUNT_DIASTOLIC;
            let eCountPulse = BP.INITIAL_VALUES.COUNT_PULSE;

            for (const item of evening) {
                // 改造BPD-135 2025.09.23 KhiemPT ---->
                if (invalidBp(item)) {
                  LoggerService.defaultWriteLog(`invalidBp evening: ${item}, ${dateStr}`);
                  continue;
                } else {
                  // Do nothing
                }
                // BPD-135 2025.09.23 KhiemPT <----
                const checkModeEvening = this.checkModeEvening(item.afib_mode);
                const isNightError = item.error_ha_night !== ERROR_CODES.NIGHT_ERROR_DEFAULT && 
                                   item.error_ha_night !== ERROR_CODES.NIGHT_ERROR_SPECIAL && 
                                   item.error_ha_night !== null;

                if (checkModeEvening || isNightError) continue;
                if (countEvening >= len) break;

                if (countEvening === 0) {
                    const measureDate = item.measure_date.substring(0, 19);
                    end = moment(measureDate);
                }
                countEvening++;

                const measureDate = item.measure_date.substring(0, 19);
                const itemTime = moment(measureDate);

                if (end && Math.abs(end.diff(itemTime, 'seconds')) < MEASURE.TIME_WINDOW.MINUTES * NUMERIC.SIXTY) {
                    if (!isNaN(Number(item.systolic))) {
                        eSystolic += Number(item.systolic);
                        eCountSystolic++;
                    }
                    if (!isNaN(Number(item.diastolic))) {
                        eDiastolic += Number(item.diastolic);
                        eCountDiastolic++;
                    }
                    if (!isNaN(Number(item.pulse))) {
                        ePulse += Number(item.pulse);
                        eCountPulse++;
                    }
                }
            }
            const ea = eCountDiastolic ? Math.round(eDiastolic / eCountDiastolic) : '--';
            const ec = eCountPulse ? Math.round(ePulse / eCountPulse) : '--';
            const eb = eCountSystolic ? Math.round(eSystolic / eCountSystolic) : '--';
            const color = this.checkColor(eb, ea, settings);

            dataReturn[dateStr].evening = `${eb}/${ea}`;
            dataReturn[dateStr].evening_pulse = ec;
            dataReturn[dateStr].evening_color = color.total;
            dataReturn[dateStr].evening_systolic_color = color.systolic;
            dataReturn[dateStr].evening_diastolic_color = color.diastolic;
            dataReturn[dateStr].evening_moment = end;
        } else {
            dataReturn[dateStr].evening = '--/--';
            dataReturn[dateStr].evening_pulse = '--';
            dataReturn[dateStr].evening_color = this.RISK_COLOR.level_0;
            dataReturn[dateStr].evening_systolic_color = this.RISK_COLOR.level_0;
            dataReturn[dateStr].evening_diastolic_color = this.RISK_COLOR.level_0;
        }
    }
    return { dataReturn, dataCount };
}

  /**
    * Check if a given mode value is an evening mode.
    * 
    * @param {number} [num] - A mode value.
    * @returns {boolean} Whether the given mode value is an evening mode.
    * 
    * @remarks
    * This function works by converting the given value to a hexadecimal string,
    * and then checking if the last two characters are equal to the expected value.
    * 
    * @example
  **/
  private static checkModeEvening(num?: number): boolean {
    if (!num) return false;
    if (num <= BP.MODE_EVENING.MIN_VALUE) return false;
    
    const hexString = num.toString(BP.MODE_EVENING.HEX_RADIX);
    const lastTwoChars = hexString.slice(-BP.MODE_EVENING.LAST_CHARS_LENGTH);
    
    return lastTwoChars === BP.MODE_EVENING.HEX_VALUE;
  }

  /**
   * Calculates the color of a given blood pressure data point based on its
   * systolic and diastolic values and the given settings.
   * 
   * @param {number|string} systolic - The systolic value of the blood pressure.
   * @param {number|string} diastolic - The diastolic value of the blood pressure.
   * @param {AppSettings} [settings] - The settings used for calculating the
   *   color. Defaults to the default settings if not provided.
   * @returns {{total: string, systolic: string, diastolic: string}} - An object
   
   */
  public static checkColor(systolic: number | string, diastolic: number | string, settings?: AppSettings) {
    const index_sys = settings?.sys ?? DEFAULT_SYS;
    const index_dia = settings?.dia ?? DEFAULT_DIA; 
    const color = {
      total: this.RISK_COLOR.level_0,
      systolic: this.RISK_COLOR.level_0,
      diastolic: this.RISK_COLOR.level_0
    };
    if (systolic === '--' || diastolic === '--') {
      return color;
    }
  
    // Convert to numbers
    const systolicNum = Number(systolic);
    const diastolicNum = Number(diastolic);
  
    if (systolicNum - index_sys >= MEASURE.SYS_THRESHOLD.HIGH || 
        diastolicNum - index_dia >= MEASURE.DIA_THRESHOLD.HIGH) {
      color.total = this.RISK_COLOR.level_4;
    } else if ((systolicNum - index_sys < MEASURE.SYS_THRESHOLD.HIGH && 
                systolicNum - index_sys >= MEASURE.SYS_THRESHOLD.MEDIUM) || 
               (diastolicNum - index_dia < MEASURE.DIA_THRESHOLD.HIGH && 
                diastolicNum - index_dia >= MEASURE.DIA_THRESHOLD.MEDIUM)) {
      color.total = this.RISK_COLOR.level_3;
    } else if ((systolicNum >= index_sys && 
                systolicNum - index_sys < MEASURE.SYS_THRESHOLD.MEDIUM) || 
               (diastolicNum >= index_dia && 
                diastolicNum - index_dia < MEASURE.DIA_THRESHOLD.MEDIUM)) {
      color.total = this.RISK_COLOR.level_2;
    } else if (systolicNum < index_sys || diastolicNum < index_dia) {
      color.total = this.RISK_COLOR.level_1;
    }
  
    // Systolic color logic. Note: This logic doesn't use from anywhere
    if (systolicNum - index_sys >= MEASURE.SYS_THRESHOLD.HIGH) {
      color.systolic = this.RISK_COLOR.level_4;
    } else if (systolicNum - index_sys < MEASURE.SYS_THRESHOLD.HIGH && 
               systolicNum - index_sys >= MEASURE.SYS_THRESHOLD.MEDIUM) {
      color.systolic = this.RISK_COLOR.level_3;
    } else if (systolicNum >= index_sys && 
               systolicNum - index_sys < MEASURE.SYS_THRESHOLD.MEDIUM) {
      color.systolic = this.RISK_COLOR.level_2;
    } else if (systolicNum < index_sys) {
      color.systolic = this.RISK_COLOR.level_1;
    }
  
    // Diastolic color logic. Note: This logic doesn't use from anywhere
    if (diastolicNum - index_dia >= MEASURE.DIA_THRESHOLD.HIGH) {
      color.diastolic = this.RISK_COLOR.level_4;
    } else if (diastolicNum - index_dia < MEASURE.DIA_THRESHOLD.HIGH && 
               diastolicNum - index_dia >= MEASURE.DIA_THRESHOLD.MEDIUM) {
      color.diastolic = this.RISK_COLOR.level_3;
    } else if (diastolicNum >= index_dia && 
               diastolicNum - index_dia < MEASURE.DIA_THRESHOLD.MEDIUM) {
      color.diastolic = this.RISK_COLOR.level_2;
    } else if (diastolicNum < index_dia) {
      color.diastolic = this.RISK_COLOR.level_1;
    }
  
    return color;
  }
  
  public static getIconByColorRisk(codeColor: string) {
    const riskIcons = {
      '#ecedf0': '',
      '#005F9E': 'iconTargetAchieved',
      '#D1406D': 'iconEqual',
      '#9b245a': 'iconHigher',
      '#4f2437': 'iconMuchHigher',
    };
    return riskIcons[codeColor];
  }

  public static convertBinary(num: number): string {
    return num.toString(2);
  }
}
