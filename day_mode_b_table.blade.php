@foreach($arr_days as $day_i => $day)
    @php
        $measure_count = count($day['measures']);
        $index_time = 1;
        $start_y_time = $current_height;
    @endphp

    <!-- Date -->
    <text transform="translate({{$start_x - $padding_left + 18.51}} {{$current_height + 15.22}})" style="font-size:6.5pt;fill:#191919;">{{$day['month']}}</text>
    <text transform="translate({{$start_x - $padding_left + 33.99}} {{$current_height + 16}})" style="font-size:10pt;fill:#191919;">{{$day['day']}}</text>
    <text transform="translate({{$start_x - $padding_left + 48.15}} {{$current_height + 15.22}})" style="font-size:6.5pt;" fill="{{in_array($day['day_week'], $sun_arr) ? '#C75882': '#000000'}}">({{$day['day_week']}})</text>

    @foreach($day['measures'] as $date_time_i => $bp)
        @php
            $start_x_time = $start_x + ($index_time == $measure_count ? 0 : (78.5 - $padding_left));
            $line_width = ($index_time == $measure_count) ? $table_width : 193.5;
        @endphp
        <!-- Time -->
        <image width="12" height="12" preserveAspectRatio="none" x="{{$start_x - $padding_left + 81}}" y="{{$start_y_time + 6.5}}" xlink:href="{{ $bp['is_mor'] ? $icon_sun :  ($bp['is_eve'] ? $icon_moon : '')}}"/>
        <text transform="translate({{$start_x - $padding_left + 95 + (strlen($bp['time']) > 4 ? 30 : 28.31)}} {{$start_y_time + 15.22}})" style="font-size:8pt;fill:#3f3f3f; text-anchor:end;">{{$bp['time']}}</text>

        <!-- Mode -->
        @if(in_array($bp['mode'],[1, 4]))
            <image width="12" height="12" x="{{$start_x - $padding_left + 127}}" y="{{$start_y_time + 6}}" xlink:href="{{ $arr_icon_images['mode'] }}"/>
        <!-- 改造BP_APP_DEV-1107 2019.11.18 TrungNH -->
        @elseif(checkModeEvening($bp['mode']) == 1)
        <!-- BP_APP_DEV-1107 2019.11.18 -->
            <image width="12" height="12" x="{{$start_x - $padding_left + 127}}" y="{{$start_y_time + 6}}" xlink:href="{{ $arr_icon_images['moon_sleep'] }}"/>
        @endif

        <!-- Sys -->
        {{-- 改造BP_APP_DEV-4229 2024.12.19 LoiNV ----> --}}
        <text transform="translate({{$start_x - $padding_left + 147 + (3 - strlen($bp['systolic']))*7}} {{$start_y_time + 16}})" style="font-size:10pt;fill:#191919;">{{$bp['systolic']}}</text>
        <image width="6" height="6" x="{{$start_x - $padding_left + 168}}" y="{{$start_y_time + 10}}" xlink:href="{{$arr_icon_images[$bp['risk_icon_systolic']]}}"/>
        {{-- 改造BP_APP_DEV-4229 2024.12.19 <---- --}}

        <!-- Dia -->
        {{-- 改造BP_APP_DEV-4229 2024.12.19 LoiNV ----> --}}
        <text transform="translate({{$start_x - $padding_left + 183  + (3 - strlen($bp['diastolic']))*7}} {{$start_y_time + 16}})" style="font-size:10pt;fill:#191919;">{{$bp['diastolic']}}</text>
        {{-- 改造BP_APP_DEV-4229 2024.12.19 <---- --}}
        <image width="6" height="6" x="{{$start_x - $padding_left + 204.5}}" y="{{$start_y_time + 10}}" xlink:href="{{$arr_icon_images[$bp['risk_icon_diastolic']]}}"/>

        <!-- Pulse -->
        {{-- 改造BP_APP_DEV-4229 2024.12.19 LoiNV ----> --}}
        <text transform="translate({{$start_x - $padding_left + 219 + (3 - strlen($bp['pulse']))*7}} {{$start_y_time + 16}})" style="font-size:10pt;fill:#191919;">{{$bp['pulse']}}</text>
        {{-- 改造BP_APP_DEV-4229 2024.12.19 <---- --}}

        <!-- D.I. -->
        <!-- First DOT -->
        @if($bp['is_error'] && !$bp['is_night_error'])
            <image width="10" height="10" x="{{$start_x - $padding_left + 246}}" y="{{$start_y_time + 6}}" xlink:href="{{$arr_icon_images['measurement_error']}}"/>
        @else
            <circle cx="{{$start_x - $padding_left + 251.5}}" cy="{{$start_y_time + 12.25}}" r="1" style="fill:#ecedf0"/>
        @endif

        <!-- Second DOT -->
        {{-- 改造BP_APP_DEV-4288 2025.02.04 LoiNV ----> --}}
        {{-- 
        If D.I is irregular pulse interval, show the irregular pulse interval icon
        Else if D.I is afib, show the afib icon
        Else if D.I is irregular heartbeat, show the irregular heartbeat icon
        Else, show the dot icon
        --}}
        @if($bp['is_irregular_pulse_interval'])
            <image width="10" height="10" x="{{$start_x - $padding_left + 259}}" y="{{$start_y_time + 6}}" xlink:href="{{$arr_icon_images['irregular_pulse_interval']}}"/>
        @elseif($bp['is_afib'] && !$bp['is_night_error'])
            <image width="10" height="10" x="{{$start_x - $padding_left + 259}}" y="{{$start_y_time + 6}}" xlink:href="{{$arr_icon_images['afib']}}"/>
        @elseif($bp['strange_pulse'] == 1 && !$bp['is_night_error'])
            <image width="10" height="10" x="{{$start_x - $padding_left + 259}}" y="{{$start_y_time + 6}}" xlink:href="{{$arr_icon_images['irregular_heartbeat']}}"/>
        @else
            <circle cx="{{$start_x - $padding_left + 263.5}}" cy="{{$start_y_time + 12.25}}" r="1" style="fill:#ecedf0"/>
        @endif
        {{-- 改造BP_APP_DEV-4288 2025.02.04 <---- --}}

        <!-- Line bp -->
        <rect x="{{$start_x_time}}" y="{{$start_y_time + 24}}" width="{{$line_width}}" height="0.5" style="fill:#ccc"/>
        @php
            $index_time++;
            $start_y_time += $row_high;
        @endphp
    @endforeach

    @if($measure_count == 0)
        <rect x="{{$start_x}}" y="{{$current_height + 24}}" width="{{$table_width}}" height="0.5" style="fill:#ccc"/>
        <!-- Time -->
        <text text-anchor="end" transform="translate({{$start_x + 110}} {{$start_y_time + 15.22}})" style="font-size:8pt;fill:#3f3f3f;">--:--</text>
        <!-- Sys -->
        {{-- 改造BP_APP_DEV-4229 2024.12.19 LoiNV ----> --}}
        <text transform="translate({{$start_x - $padding_left + 161}} {{$start_y_time + 16}})" style="font-size:10pt;fill:#191919;">-</text>
        {{-- 改造BP_APP_DEV-4229 2024.12.19 <---- --}}
        <!-- Dia -->
        {{-- 改造BP_APP_DEV-4229 2024.12.19 LoiNV ----> --}}
        <text transform="translate({{$start_x - $padding_left + 197}} {{$start_y_time + 16}})" style="font-size:10pt;fill:#191919;">-</text>
        {{-- 改造BP_APP_DEV-4229 2024.12.19 <---- --}}
        <!-- Pulse -->
        {{-- 改造BP_APP_DEV-4229 2024.12.19 LoiNV ----> --}}
        <text transform="translate({{$start_x - $padding_left + 233}} {{$start_y_time + 16}})" style="font-size:10pt;fill:#191919;">-</text>
        {{-- 改造BP_APP_DEV-4229 2024.12.19 <---- --}}
        <!-- D.I. -->
        <circle cx="{{$start_x - $padding_left + 251.5}}" cy="{{$start_y_time + 12.25}}" r="1" style="fill:#ecedf0"/>
        <circle cx="{{$start_x - $padding_left + 263.5}}" cy="{{$start_y_time + 12.25}}" r="1" style="fill:#ecedf0"/>
    @endif

    <!-- Med -->
    @php
        if($measure_count == 0){
                $y_med = $current_height + 8;
        }else if($measure_count == 1){
                $start_y_notes = $current_height + 8;
                $y_med = $current_height + 8;
        }else{
                $y_med = $current_height + $measure_count*$row_high/2 - 6;
        }
        $medicineIconFirst = $icon_medicine;
        $medicineIconSecond = $icon_medicine;
        $medicineIconThird = $icon_medicine;
        $medicineIconFirst = $day['med']['first'] ? $icon_medicine_active : $icon_medicine;
        $medicineIconSecond = $day['med']['second'] ? $icon_medicine_active : $icon_medicine;
        $medicineIconThird = $day['med']['third'] ? $icon_medicine_active : $icon_medicine;
    @endphp

    <!-- // 改造BP_APP_DEV-1103 2019.09.10 TranHV ---->
    @if($med_per_day == 3)
        <image width="10" height="10" x="{{$start_x - $padding_left + 274 }}"
                                        y="{{ $y_med }}"
                                        xlink:href="{{ $medicineIconFirst }}"/>
                    
        <image width="10" height="10" x="{{$start_x - $padding_left + 284 }}"
                        y="{{ $y_med }}"
                        xlink:href="{{ $medicineIconSecond }}"/>

        <image width="10" height="10" x="{{$start_x - $padding_left + 294 }}"
                        y="{{ $y_med }}"
                        xlink:href="{{ $medicineIconThird }}"/>
    @elseif($med_per_day == 2)
        <image width="10" height="10" x="{{$start_x - $padding_left + 279}}"
                                        y="{{ $y_med }}"
                                        xlink:href="{{ $medicineIconFirst }}"/>
        <image width="10" height="10" x="{{$start_x - $padding_left + 289}}"
                                    y="{{ $y_med }}"
                                    xlink:href="{{ $medicineIconSecond }}"/>
    @elseif($med_per_day == 1)
        <image width="10" height="10" x="{{$start_x - $padding_left + 284 }}"
                    y="{{ $y_med }}"
                    xlink:href="{{ $medicineIconFirst }}"/>
    @endif
    <!-- // BP_APP_DEV-1103 2019.09.10 <---- -->

    <!-- Note -->
    @php
        $start_x_icons = $start_x - $padding_left + 307;
        $start_x_message = $start_x - $padding_left + 310;
        $start_y_message = $current_height + 20.66;
        $start_y_icons = $current_height + 6;
        $count_icon = 0;
        $note_width = 170;
    @endphp
    @if(array_key_exists('mask_values', $day))
        @foreach($day['mask_values'] as $mask_index => $mask)
            @if(in_array($mask_index, ['medication_1', 'medication_2', 'medication_3']))
                @php
                    continue;
                @endphp;
            @endif

            @if($mask && $mask_index == 'hospital')
                <image width="10" height="10" x="{{$start_x - $padding_left + 64.5}}" y="{{$current_height + 6.25}}" xlink:href="{{$arr_icon_notes[$mask_index]}}"/>
                @php
                    continue;
                @endphp;
            @endif
            @if($mask && $mask_index != 'message')
                @if($note_width >= 18)
                    <image width="8" height="8" x="{{$start_x_icons}}" y="{{$start_y_icons}}" xlink:href="{{$arr_icon_notes[$mask_index]}}"/>
                @else
                    <text transform="translate({{$start_x_icons}} {{$start_y_icons + 5}})" style="font-size:7pt;">...</text>
                    @php
                        break;
                    @endphp
                @endif

                @php
                    $start_x_icons += 10;
                    $count_icon++;
                    $note_width -= 18;
                @endphp
            @endif
        @endforeach
        @if($day['mask_values']['message'])
            @php
                $msg = $day['mask_values']['message'];
                $arr_notes = splitStringByWidthImg($msg, 100, $font_file);
                $msg = count($arr_notes) > 1 ? $arr_notes[0].' ...' : $msg;
            @endphp
            @if($count_icon == 0)
                <text transform="translate({{$start_x_message}} {{$start_y_icons + 5}})" style="font-size:7pt;">{{$msg}}</text>
            @else
                <text transform="translate({{$start_x_message}} {{$start_y_message}})" style="font-size:7pt;">{{$msg}}</text>
            @endif
        @endif
    @endif

    @php
        $current_height += $row_high*($measure_count == 0 ? 1 : $measure_count);
    @endphp
@endforeach

@php
    $remain_height = $height - $current_height - 2*$row_high;
@endphp
@if($remain_height > $row_high)
    @for( $i = 0; $i < $remain_height/$row_high; $i++)
        <rect x="{{$start_x}}" y="{{$current_height + $i*$row_high}}" width="{{$table_width}}" height="0.5" style="fill:#ccc"/>
    @endfor
@endif