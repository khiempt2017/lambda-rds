@php
    $row_high = 24;
    $width = 840;
    $height  = 594 - $row_high;
    $table_width = 392;
    $table_height = 376 - $row_high;
    $padding_left = 14;
    $row_high_header = 15.5;

    $line_thin = 0.5;

    $header_high = 32;

    $column_width_period = 80;
    $column_width_period = 80;
    $sun_arr = ['Sun', '日'];
    $region = env("APP_REGION");
@endphp

<!-- Line on top -->
<rect x="{{$start_x - $padding_left + 14}}" y="62" width="392" height="0.5" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 212.5}}" y="62" width="0.5" height="113.5" style="fill:#a6aab2"/>

<!-- Box -->
@if($region == 'JP')
    <rect x="{{$start_x - $padding_left + 14}}" y="62.5" width="40" height="15" style="fill:#c1c6d5;opacity:0.2"/>
@else
    <rect x="{{$start_x - $padding_left + 14}}" y="62.5" width="110" height="15" style="fill:#c1c6d5;opacity:0.2"/>
@endif
<rect x="{{$start_x - $padding_left + 212.5}}" y="62.5" width="50" height="15" style="fill:#c1c6d5;opacity:0.2"/>

<!-- Text box -->
@if($region == 'JP')
    <text transform="translate({{$start_x - $padding_left + 18}} 72.16)" style="font-size:7pt;fill:#4d5266;">{{ $app_lang['PDF_WEEK_HINT_HEADER']}}</text>
@else
    <text transform="translate({{$start_x - $padding_left + 18}} 72.16)" style="font-size:7pt;fill:#4d5266;">{{ $app_lang['PDF_WEEK_HINT_MODE_HEADER']}}</text>
@endif
<text transform="translate({{$start_x - $padding_left + 216}} 72.66)" style="font-size:7pt;fill:#4d5266;">{{ $app_lang['PDF_WEEK_HIND_NOTES']}}</text>

<!-- Left hint -->
@if($region == 'JP')

<text transform="translate({{$start_x - $padding_left + 32}} {{111.65 - 22.5}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MODE_NIGHT']}}</text>
<text transform="translate({{$start_x - $padding_left + 32}} {{119.84 - 22.5}})" style="font-size:6pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MODE_NIGHT_DES']}}</text>

<text transform="translate({{$start_x - $padding_left + 32}} {{136.22 + 5 - 30}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MEASUREMENT_ERROR']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 LoiNV ----> --}}
<text transform="translate({{$start_x - $padding_left + 32}} {{144.85 + 5 - 30}})" style="font-size:6pt;">{{ $app_lang['PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1']}}
    <tspan x="0" y="7">{{ $app_lang['PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2']}}</tspan>
</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 <---- --}}

<text transform="translate({{$start_x - $padding_left + 32}} {{136.22 + 5}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_IRREGULAR_HEARTBEAT']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 LoiNV ----> --}}
<text transform="translate({{$start_x - $padding_left + 32}} {{144.85 + 5}})" style="font-size:6pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 <---- --}}

{{-- 改造BP_APP_DEV-4288 2025.02.10 LoiNV ----> --}}
<text transform="translate({{$start_x - $padding_left + 32}} 165)" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL']}}</text>
<text transform="translate({{$start_x - $padding_left + 32}} 173)" style="font-size:6pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_IRREGULAR_PULSE_INTERVAL_DESC']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.10 <---- --}}

@else
<text transform="translate({{$start_x - $padding_left + 32}} 89.15)" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MODE']}}</text>
<text transform="translate({{$start_x - $padding_left + 32}} 97.34)" style="font-size:6pt;">{{ $app_lang['PDF_WEEK_HINT_MODE_DES']}}</text>

<text transform="translate({{$start_x - $padding_left + 32}} {{89.15 + 22.5}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MODE_NIGHT']}}</text>
<text transform="translate({{$start_x - $padding_left + 32}} {{89.15 + 22.5 + 7.24}})" style="font-size:5.5pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MODE_NIGHT_DES']}}</text>

<text transform="translate({{$start_x - $padding_left + 32}} {{111.65 + 20}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_MEASUREMENT_ERROR']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 LoiNV ----> --}}
<text transform="translate({{$start_x - $padding_left + 32}} {{119.84 + 20}})" style="font-size:6pt;">{{ $app_lang['PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_1']}}
    <tspan x="0" y="7">{{ $app_lang['PDF_WEEK_HINT_MEASUREMENT_ERROR_DESC_2']}}</tspan>
</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 <---- --}}

<text transform="translate({{$start_x - $padding_left + 32}} {{136.22 + 5 + 20}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_IRREGULAR_HEARTBEAT']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 LoiNV ----> --}}
<text transform="translate({{$start_x - $padding_left + 32}} {{144.85 + 5 + 20}})" style="font-size:6pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_IRREGULAR_HEARTBEAT_DESC']}}</text>
{{-- 改造BP_APP_DEV-4288 2025.02.11 <---- --}}

<text transform="translate({{$start_x - $padding_left + 32}} {{161.1 + 5 + 20}})" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_AFIB']}}</text>
{{-- 改造BP_APP_DEV-4599 2025.04.14 LoiNV ----> --}}
<text transform="translate({{$start_x - $padding_left + 32}} {{169.34 + 5 + 20}})" style="font-size:6pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_AFIB_DESC']}}</text>
{{-- 改造BP_APP_DEV-4599 2025.04.14 <---- --}}
@endif

<!-- Icon left -->
@if($region == 'JP')
    <image width="12" height="12" x="{{$start_x - $padding_left + 18}}" y="{{103 - 20}}" xlink:href="{{$arr_icon_images['moon_sleep']}}" />
    <image width="12" height="12" x="{{$start_x - $padding_left + 18}}" y="{{129 + 5 - 30}}" xlink:href="{{$arr_icon_images['measurement_error']}}" />
    <image width="10" height="10" x="{{$start_x - $padding_left + 18}}" y="{{129 + 7}}" xlink:href="{{$arr_icon_images['irregular_heartbeat']}}"/>
    {{-- 改造BP_APP_DEV-4288 2025.02.10 LoiNV ----> --}}
    <image width="10" height="10" x="{{$start_x - $padding_left + 18}}" y="159" xlink:href="{{$arr_icon_images['irregular_pulse_interval']}}"/>
    {{-- 改造BP_APP_DEV-4288 2025.02.10 <---- --}}
@else
    <image width="12" height="12" x="{{$start_x - $padding_left + 18}}" y="{{82.5}}" xlink:href="{{$arr_icon_images['mode']}}"/>
    <image width="12" height="12" x="{{$start_x - $padding_left + 18}}" y="{{82.5 + 20}}" xlink:href="{{$arr_icon_images['moon_sleep']}}"/>
    <image width="12" height="12" x="{{$start_x - $padding_left + 18}}" y="{{103 + 20}}" xlink:href="{{$arr_icon_images['measurement_error']}}"/>
    <image width="10" height="10" x="{{$start_x - $padding_left + 18}}" y="{{129.07 + 5  + 20}}" xlink:href="{{$arr_icon_images['irregular_heartbeat']}}"/>
    <image width="12" height="12" x="{{$start_x - $padding_left + 18}}" y="{{154.09 + 5  + 20}}" xlink:href="{{$arr_icon_images['afib']}}"/>
@endif

<!-- Right icon -->
<!-- Left icon hint -->
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83}}" xlink:href="{{$arr_icon_notes['exercise']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83 + 12}}" xlink:href="{{$arr_icon_notes['un_salt']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83 + 24}}" xlink:href="{{$arr_icon_notes['veggie']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83 + 36}}" xlink:href="{{$arr_icon_notes['un_drink']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83 + 48}}" xlink:href="{{$arr_icon_notes['sleep']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83 + 60}}" xlink:href="{{$arr_icon_notes['un_smoking']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 220}}" y="{{83 + 72}}" xlink:href="{{$arr_icon_notes['hospital']}}"/>

<text transform="translate({{$start_x - $padding_left + 234}} 89.65)" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_EXCERCISE']}}
    <tspan x="0" y="12">{{ $app_lang['PDF_WEEK_HINT_REDUCING_SALT']}}</tspan>
    <tspan x="0" y="24">{{ $app_lang['PDF_WEEK_HINT_VEGETABLES']}}</tspan>
    <tspan x="0" y="36">{{ $app_lang['PDF_WEEK_HINT_NO_ALCOHOL']}}</tspan>
    <tspan x="0" y="48">{{ $app_lang['PDF_WEEK_HINT_SLEEP']}}</tspan>
    <tspan x="0" y="60">{{ $app_lang['PDF_WEEK_HINT_NOT_SMOKING']}}</tspan>
    <tspan x="0" y="72">{{ $app_lang['PDF_WEEK_HINT_CONSULTATION']}}</tspan>
</text>

<!-- Column 2 -->
<image width="10" height="10" x="{{$start_x - $padding_left + 314}}" y="{{83}}" xlink:href="{{$arr_icon_notes['un_exercise']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 314}}" y="{{83 + 12}}" xlink:href="{{$arr_icon_notes['salt']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 314}}" y="{{83 + 24}}" xlink:href="{{$arr_icon_notes['un_veggie']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 314}}" y="{{83 + 36}}" xlink:href="{{$arr_icon_notes['drink']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 314}}" y="{{83 + 48}}" xlink:href="{{$arr_icon_notes['un_sleep']}}"/>
<image width="10" height="10" x="{{$start_x - $padding_left + 314}}" y="{{83 + 60}}" xlink:href="{{$arr_icon_notes['smoking']}}"/>

<text transform="translate({{$start_x - $padding_left + 328}} 89.65)" style="font-size:7pt;fill:#191919;">{{ $app_lang['PDF_WEEK_HINT_LACK_OF_EXCERCISE']}}
    <tspan x="0" y="12">{{ $app_lang['PDF_WEEK_HINT_SALT']}}</tspan>
    <tspan x="0" y="24">{{ $app_lang['PDF_WEEK_HINT_LESS_VEGETABLES']}}</tspan>
    <tspan x="0" y="36">{{ $app_lang['PDF_WEEK_HINT_ALCOHOL']}}</tspan>
    <tspan x="0" y="48">{{ $app_lang['PDF_WEEK_HINT_LACK_OF_SLEEP']}}</tspan>
    <tspan x="0" y="60">{{ $app_lang['PDF_WEEK_HINT_SMOKING']}}</tspan>
</text>

<!-- ===== TABLE ==== -->
<!-- Fill header -->
<rect x="{{$start_x - $padding_left + 14}}" y="{{182 + $row_high}}" width="392" height="16" style="fill:#c1c6d5;opacity:0.2"/>
<rect x="{{$start_x - $padding_left + 14.25}}" y="{{198 + $row_high}}" width="106.75" height="{{359.44 - $row_high}}" style="fill:#c1c6d5;opacity:0.2"/>

<!-- Line row -->
<rect x="{{$start_x - $padding_left + 14}}" y="{{182 + $row_high}}" width="392" height="0.5" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 14}}" y="{{197.5 + $row_high}}" width="392" height="0.5" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 14}}" y="{{557.5}}" width="392" height="0.5" style="fill:#a6aab2"/>

<!-- Line column -->
<rect x="{{$start_x - $padding_left + 14}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 78.5}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 121}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>

<path fill="none" stroke="#a6aab2" paint-order="fill stroke markers" d=" M {{144.5 + $start_x - $padding_left}} {{182.5 + $row_high}} L {{144.5 + $start_x - $padding_left}} {{$table_height + 182.5 + $row_high}}" stroke-linecap="square" stroke-miterlimit="10" stroke-width="0.5" stroke-dasharray="1.5,4"></path>
<path fill="none" stroke="#a6aab2" paint-order="fill stroke markers" d=" M {{180.5 + $start_x - $padding_left}} {{182.5 + $row_high}} L {{180.5 + $start_x - $padding_left}} {{$table_height + 182.5 + $row_high}}" stroke-linecap="square" stroke-miterlimit="10" stroke-width="0.5" stroke-dasharray="1.5,4"></path>
<path fill="none" stroke="#a6aab2" paint-order="fill stroke markers" d=" M {{217 + $start_x - $padding_left}} {{182.5 + $row_high}} L {{217 + $start_x - $padding_left}} {{$table_height + 182.5 + $row_high}}" stroke-linecap="square" stroke-miterlimit="10" stroke-width="0.5" stroke-dasharray="1.5,4"></path>

<rect x="{{$start_x - $padding_left + 243}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 271.5}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 306}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>
<rect x="{{$start_x - $padding_left + 405.5}}" y="{{182 + $row_high}}" width="0.5" height="{{$table_height}}" style="fill:#a6aab2"/>

<!-- Text header -->
<text transform="translate({{$start_x - $padding_left + 39.07}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_DATE']}}</text>
<text transform="translate({{$start_x - $padding_left + 92.25 - 7}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_TIME']}}</text>
<text transform="translate({{$start_x - $padding_left + 123.4}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_HINT_MODE']}}</text>
<text transform="translate({{$start_x - $padding_left + 156.29 - 8}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_SYS']}}</text>
<text transform="translate({{$start_x - $padding_left + 192.82 - 8}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_DIA']}}</text>
<text transform="translate({{$start_x - $padding_left + 221.7}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_PULSE']}}</text>
<text transform="translate({{$start_x - $padding_left + 252.59 - 9}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_DI']}}</text>
<text transform="translate({{$start_x - $padding_left + 280.7 }} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_MED']}}</text>
<text transform="translate({{$start_x - $padding_left + 346.85 - 15}} {{192.65 + $row_high}})" style="font-size:7pt;fill:#4d5266;">{{$app_lang['PDF_WEEK_NOTES']}}</text>

<!-- Fill Data -->
@php
    $current_height = 197.5 + $row_high;
    $sun_arr = ['Sun', '日'];
@endphp
@include("svg{$sub_blade}.week_template.day_mode_b_table", ['current_height' => 197.5 + $row_high])