<?php

/**
 * Check if the passed date is a closed date
 *
 * @param $ocData
 * @param bool $date
 * @return bool
 */
function oc_checkHoliday($ocData, $date = false)
{
    $isHoliday = false;
    if ($date) {
        if (isset($ocData['oc_holiday_' . substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . substr($date, 8, 2)])) {
            $isHoliday = true;
        }
    } else {
        if (isset($ocData['oc_holiday_' . date('Y', current_time('timestamp')) . '-' . date('m', current_time('timestamp')) . '-' . date('d', current_time('timestamp'))])) {
            $isHoliday = true;
        }
    }
    return $isHoliday;
}

/**
 * Check for extra open dates
 *
 * @param array $ocData
 * @return array
 */
function oc_checkExtraDates($ocData)
{
    $toReturn = array(
        'extraIndex' => false,
        'closeTime'  => false,
        'openDate'   => false,
        'openTime'   => false,
        'isOpen'     => false,
        'currentlySpecial'=> false,
        'wasSpecial'=> false,
    );

    $weekdays = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wed' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag');

    $currentYear   = date('Y', current_time('timestamp'));
    $currentMonth  = date('n', current_time('timestamp'));
    $currentDay    = date('j', current_time('timestamp'));
    $currentHour   = date('G', current_time('timestamp'));
    $currentMinute = date('i', current_time('timestamp'));
    $currentWeekday = lcfirst(date('D', current_time('timestamp')));


    foreach($ocData['oc_special_days'] as $index => $special){

        // get the Extra Data
        list($extraDay, $extraMonth, $extraYear) = explode('.', $special['date']);

        // get the starting hour and Minute
        list($extraHourStart, $extraMinuteStart) = explode(':',$special['from']);

        // get the ending hour and minute
        list($extraHourEnd, $extraMinuteEnd) = explode(':',$special['until']);

        // check for currently open and future opening at the same day
        if ($extraYear  == $currentYear &&
            $extraMonth == $currentMonth &&
            $extraDay   == $currentDay
        ) {
            // is currently an extra opening time?
            if (
                // check if NOW is between start and end hours
                ( $extraHourStart < $currentHour && $currentHour < $extraHourEnd )
                // check if NOW is after starting Minute
                || ( $extraHourStart  == $currentHour && $extraMinuteStart <= $currentMinute )
                // check if NOW is before ending Minute
                || ( $extraHourEnd >= $currentHour && $extraMinuteEnd > $currentMinute )
            ) {
                $toReturn['currentlySpecial'] = true;
                $toReturn['extraIndex'] = $index;
                $toReturn['closeTime'] = $special['until'];
                $toReturn['text'] = $special['text'];
                $toReturn['link'] = $special['link'];
            }
            // check if later today will be a extra day
            else if (
                // extradate starts in a future hour
                $extraHourStart > $currentHour
                // extradate starts in a future minute
                || ($extraHourStart == $currentHour && $extraMinuteStart > $currentMinute)
            ) {
                $toReturn['extraIndex'] = $index;
                $toReturn['openDate'] = $currentYear . '-' . date('m', current_time('timestamp')) . '-' . date('d', current_time('timestamp'));
                $toReturn['openDateString'] = 'heutigen ' . $weekdays[$currentWeekday] . ', ';
                $toReturn['openTime'] = $special['from'];
                $toReturn['closeTime'] = $special['until'];
                $toReturn['text'] = $special['text'];
                $toReturn['link'] = $special['link'];
            }
            // there WAS a special day today
            else if (
                $extraHourEnd < $currentHour
                || $extraHourEnd == $currentHour && $extraMinuteEnd < $currentMinute
            ) {
                $toReturn['wasSpecial'] = true;
            }
            break;
        }
        // check for next opening in the future
        else if (

            // is next year
            $extraYear > $currentYear
            // or is this year but future month
            || $extraYear == $currentYear && $extraMonth > $currentMonth
            // or this year, this month but future day
            || $extraYear == $currentYear && $extraMonth == $currentMonth && $extraDay > $currentDay
        ) {
            $currentExtraOpenDate = $extraYear . '-' . str_pad($extraMonth, 2, 0, STR_PAD_LEFT) . '-' . str_pad($extraDay, 2, 0, STR_PAD_LEFT);

            // if current running extradate is earlier than other date
            // (find earliest special date)
            if (!$toReturn['openDate'] || $toReturn['openDate'] > $currentExtraOpenDate) {
                $toReturn['extraIndex'] = $index;
                $toReturn['openDate'] = $currentExtraOpenDate;
                $toReturn['openDateString'] = $extraDay . '.' . $extraMonth . '.';
                $toReturn['openTime'] = $special['from'];
            }
        }
    }
    return $toReturn;
}

function oc_checkWeekday($ocData, $weekday, $j, $k)
{

    $toReturn = array(
        'dayIndex' => false,
        'closeTime' => false,
        'openDate' => false,
        'openDateString' => false,
        'openTime' => false,
    );

    $weekdays = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wed' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag');

    if (!isset($ocData['oc_closed_' . $weekday . '_' . $j]) || !isset( $ocData['oc_closed_' . $weekday . '_' . $j] )) {
        // Calculates next calendar day.
        $openDate = date('Y-m-d', strtotime(sprintf('%s + %d day', date('Y-m-d', current_time('timestamp')), $k)));

        if (!oc_checkHoliday($ocData, $openDate)) {
            // Gets the weekday for next open day.
            $openWeekday = array_keys($weekdays)[(int) date('N', strtotime($openDate)) - 1];

            $fromHour1 = substr($ocData['oc_' . $openWeekday . '_from_' . $j . '_1'], 0, strpos($ocData['oc_' . $openWeekday . '_from_' . $j . '_1'], ':'));
            $fromMinute1 = intval(substr($ocData['oc_' . $openWeekday . '_from_' . $j . '_1'], strpos($ocData['oc_' . $openWeekday . '_from_' . $j . '_1'], ':') + 1));

            $fromHour2 = substr($ocData['oc_' . $openWeekday . '_from_' . $j . '_2'], 0, strpos($ocData['oc_' . $openWeekday . '_from_' . $j . '_2'], ':'));
            $fromMinute2 = intval(substr($ocData['oc_' . $openWeekday . '_from_' . $j . '_2'], strpos($ocData['oc_' . $openWeekday . '_from_' . $j . '_2'], ':') + 1));

            if (is_numeric($fromHour1) && (!is_numeric($fromHour2) or $fromHour1 < $fromHour2 or $fromHour1 == $fromHour2 and $fromMinute1 <= $fromMinute2)) {

                $toReturn['openTime'] = $ocData['oc_' . $openWeekday . '_from_' . $j . '_1'];


                $toReturn['openDate'] = $openDate;


                $toReturn['openDateString'] = $weekdays[$openWeekday];

            } else if (is_numeric($fromHour2)) {

                $toReturn['openTime'] = $ocData['oc_' . $weekday . '_from_' . $j . '_2'];
                $toReturn['openDate'] = $openDate;
                $toReturn['openDateString'] = $weekdays[$weekday];
            }
        }
    }

    return $toReturn;
}

function oc_getOpenTimes($ocData)
{

    $currentMonth = date('n', current_time('timestamp'));
    $currentDay = date('j', current_time('timestamp'));

    $toReturn = '<div class="oc_opentimesoverview">';
    $returnArray = array();
    $closedPart = '';


    $weekdays = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wed' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag');

    for ($j = 1; $j <= 2; $j++) {

        $matchingDay = false;

        if ($ocData['oc_opentimes_from_month_' . $j] < $currentMonth &&
            $currentMonth < $ocData['oc_opentimes_until_month_' . $j]
        ) {

            $matchingDay = true;

        } else if ($ocData['oc_opentimes_from_month_' . $j] == $currentMonth && $currentMonth < $ocData['oc_opentimes_until_month_' . $j] &&
            $ocData['oc_opentimes_from_day_' . $j] <= $currentDay
        ) {

            $matchingDay = true;

        } else if ($ocData['oc_opentimes_until_month_' . $j] == $currentMonth &&
            $currentDay <= $ocData['oc_opentimes_until_day_' . $j]
        ) {

            $matchingDay = true;
        } // cases with year change in between:
        else if ($ocData['oc_opentimes_from_month_' . $j] > $ocData['oc_opentimes_until_month_' . $j] and (
                $ocData['oc_opentimes_from_month_' . $j] < $currentMonth or $currentMonth < $ocData['oc_opentimes_until_month_' . $j])
        ) {

            $matchingDay = true;
        }


        // we are only interested in times in the currently active time span
        if ($matchingDay) {

            foreach ($weekdays as $weekdayKey => $weekdayValue) {

                $openTime1 = $ocData['oc_' . $weekdayKey . '_from_' . $j . '_1'];
                $openTime2 = $ocData['oc_' . $weekdayKey . '_from_' . $j . '_2'];

                $closeTime1 = $ocData['oc_' . $weekdayKey . '_until_' . $j . '_1'];
                $closeTime2 = $ocData['oc_' . $weekdayKey . '_until_' . $j . '_2'];

                if ($openTime1 != '--:--' && $closeTime1 != '--:--' && $openTime2 != '--:--' && $closeTime2 != '--:--' && (!isset($ocData['oc_closed_' . $weekdayKey . '_' . $j]) || !isset( $ocData['oc_closed_' . $weekdayKey . '_' . $j] ))) {
                    $returnArray[] = array($weekdayValue, $openTime1 . ' - ' . $closeTime1 . ' und ' . $openTime2 . ' - ' . $closeTime2);
                } else if ($openTime1 != '--:--' && $closeTime1 != '--:--' && !isset( $ocData['oc_closed_' . $weekdayKey . '_' . $j] )) {
                    $returnArray[] = array($weekdayValue, $openTime1 . ' - ' . $closeTime1);
                } else if ($openTime2 != '--:--' && $closeTime2 != '--:--' && !isset( $ocData['oc_closed_' . $weekdayKey . '_' . $j] )) {
                    $returnArray[] = array($weekdayValue, $openTime2 . ' - ' . $closeTime2);
                } else {
                    $returnArray[] = array($weekdayValue, 'geschlossen');
                }
            }
        }
    }

    $openTimesArr = array();
    $weekDayOpenArr = array();

    foreach ($returnArray as $day) {

        if (in_array($day[1], $openTimesArr)) {
            $weekDayOpenArr[$day[0]] = array_search($day[1], $openTimesArr);
        } else {
            $openTimesArr[] = $day[1];
            $weekDayOpenArr[$day[0]] = array_search($day[1], $openTimesArr);
        }
    }

    $negOpenTimes = array();

    for ($i = 0; $i < 7; $i++) {

        $elemArr = array();

        foreach ($weekDayOpenArr as $weekday => $openKey) {
            if ($openKey == $i) {

                $elemArr[] = substr($weekday, 0, 2) . '.';
            }
        }
        $dayCount = sizeof($elemArr);
        if ($dayCount == 1) {
            $negOpenTimes[$i] = $elemArr[0];
        } else if ($dayCount == 2) {
            $negOpenTimes[$i] = implode(', ', $elemArr);
        } else if (oc_followingdays($elemArr)) {
            $negOpenTimes[$i] = $elemArr[0] . ' - ' . $elemArr[$dayCount - 1];
        } else if ($dayCount > 0) {
            $negOpenTimes[$i] = implode(', ', $elemArr);
        }

    }

    foreach ($negOpenTimes as $index => $entry) {
        if ($openTimesArr[$index] == 'geschlossen') {
            $closedPart = PHP_EOL . '<div><span>' . $entry . ':</span> ' . $openTimesArr[$index] . '</div>';
        } else {
            //build Schema meta information
            $metaTimes = explode('und', str_replace(' ', '', $openTimesArr[$index]));
            $metaDays  = str_replace(' ', '', str_replace('.', '', $entry));

            $toReturn .= PHP_EOL . '<div>';
            foreach ($metaTimes as $metaTime){
                $toReturn .= '<meta itemprop="openingHours" content="' . $metaDays .' ' . $metaTime . '">';
            }
            $toReturn .= '<span>' . $entry . ':</span> ' . $openTimesArr[$index];
            $toReturn .= '</div>';

        }
    }
    $toReturn .= $closedPart;


    $toReturn .= PHP_EOL . '</div>';

    return $toReturn;

}

function oc_getOpenTimesFixed($ocData, $section)
{
    $toReturn = '<div class="oc_opentimesoverview">';
    $returnArray = array();
    $closedPart = '';

    $weekdays = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wed' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag');

    foreach ($weekdays as $weekdayKey => $weekdayValue) {

        $openTime1 = $ocData['oc_' . $weekdayKey . '_from_' . $section . '_1'];
        $openTime2 = $ocData['oc_' . $weekdayKey . '_from_' . $section . '_2'];

        $closeTime1 = $ocData['oc_' . $weekdayKey . '_until_' . $section . '_1'];
        $closeTime2 = $ocData['oc_' . $weekdayKey . '_until_' . $section . '_2'];

        if ($openTime1 != '--:--' && $closeTime1 != '--:--' && $openTime2 != '--:--' && $closeTime2 != '--:--' && !isset( $ocData['oc_closed_' . $weekdayKey . '_1'] ) && !isset( $ocData['oc_closed_' . $weekdayKey . '_2'] )) {
            $returnArray[] = array($weekdayValue, $openTime1 . ' - ' . $closeTime1 . ' und ' . $openTime2 . ' - ' . $closeTime2);
        } else if ($openTime1 != '--:--' && $closeTime1 != '--:--' && !isset( $ocData['oc_closed_' . $weekdayKey . '_' . $section] )) {
            $returnArray[] = array($weekdayValue, $openTime1 . ' - ' . $closeTime1);
        } else if ($openTime2 != '--:--' && $closeTime2 != '--:--' && !isset( $ocData['oc_closed_' . $weekdayKey . '_' . $section] )) {
            $returnArray[] = array($weekdayValue, $openTime2 . ' - ' . $closeTime2);
        } else {
            $returnArray[] = array($weekdayValue, 'geschlossen');
        }
    }

    $openTimesArr = array();
    $weekDayOpenArr = array();

    foreach ($returnArray as $day) {

        if (in_array($day[1], $openTimesArr)) {
            $weekDayOpenArr[$day[0]] = array_search($day[1], $openTimesArr);
        } else {
            $openTimesArr[] = $day[1];
            $weekDayOpenArr[$day[0]] = array_search($day[1], $openTimesArr);
        }
    }

    $negOpenTimes = array();

    for ($i = 0; $i < 7; $i++) {

        $elemArr = array();

        foreach ($weekDayOpenArr as $weekday => $openKey) {
            if ($openKey == $i) {

                $elemArr[] = substr($weekday, 0, 2) . '.';
            }
        }
        $dayCount = sizeof($elemArr);
        if ($dayCount == 1) {
            $negOpenTimes[$i] = $elemArr[0];
        } else if ($dayCount == 2) {
            $negOpenTimes[$i] = implode(', ', $elemArr);
        } else if (oc_followingdays($elemArr)) {
            $negOpenTimes[$i] = $elemArr[0] . ' - ' . $elemArr[$dayCount - 1];
        } else if ($dayCount > 0) {
            $negOpenTimes[$i] = implode(', ', $elemArr);
        }

    }
    foreach ($negOpenTimes as $index => $entry) {
        if ($openTimesArr[$index] == 'geschlossen') {
            $closedPart = '<div>' . $entry . ': ' . $openTimesArr[$index] . '</div>';
        } else {
            $toReturn .= '<div>' . $entry . ': ' . $openTimesArr[$index] . ' Uhr</div>';
        }
    }
    $toReturn .= $closedPart;


    $toReturn .= '</div>';

    return $toReturn;
}

function oc_followingdays($dayarr)
{
    $weekdaysflat = 'Mo.Di.Mi.Do.Fr.Sa.So.';
    $dayarrflat = implode('', $dayarr);
    if (empty($dayarrflat)) return false;
    return strpos($weekdaysflat, $dayarrflat) !== false;
}

function oc_checkStandardDates($ocData)
{

    $toReturn = array(
        'dayIndex' => false,
        'closeTime' => false,
        'openDate' => false,
        'openDateString' => false,
        'openTime' => false,
    );

    $weekdays = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wed' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag');

    $weekdayAssoc = array(
        0 => 'mon',
        1 => 'tue',
        2 => 'wed',
        3 => 'thu',
        4 => 'fri',
        5 => 'sat',
        6 => 'sun',
        7 => 'mon',
        8 => 'tue',
        9 => 'wed',
        10 => 'thu',
        11 => 'fri',
        12 => 'sat',
        13 => 'sun',
        14 => 'mon',
        15 => 'tue',
        16 => 'wed',
        17 => 'thu',
        18 => 'fri',
        19 => 'sat',
        20 => 'sun',
        21 => 'mon',
        22 => 'tue',
        23 => 'wed',
        24 => 'thu',
        25 => 'fri',
        26 => 'sat',
        27 => 'sun',
    );

    $currentYear = date('Y', current_time('timestamp'));
    $currentMonth = date('n', current_time('timestamp'));
    $currentDay = date('j', current_time('timestamp'));
    $currentWeekday = lcfirst(date('D', current_time('timestamp')));
    $currentHour = intval(date('G', current_time('timestamp')));
    $currentMinute = intval(date('i', current_time('timestamp')));
    if ($ocData['wasSpecial']){
        $currentHour = 23;
        $currentMinute = 59;
    }


    for ($j = 1; $j <= 2; $j++) {

        $matchingDay = false;

        if ($ocData['oc_opentimes_from_month_' . $j] < $currentMonth &&
            $currentMonth < $ocData['oc_opentimes_until_month_' . $j]
        ) {

            $matchingDay = true;

        } else if ($ocData['oc_opentimes_from_month_' . $j] == $currentMonth && $currentMonth < $ocData['oc_opentimes_until_month_' . $j] &&
            $ocData['oc_opentimes_from_day_' . $j] <= $currentDay
        ) {

            $matchingDay = true;

        } else if ($ocData['oc_opentimes_until_month_' . $j] == $currentMonth &&
            $currentDay <= $ocData['oc_opentimes_until_day_' . $j]
        ) {

            $matchingDay = true;
        } // cases with year change in between:
        else if ($ocData['oc_opentimes_from_month_' . $j] > $ocData['oc_opentimes_until_month_' . $j] and (
                $ocData['oc_opentimes_from_month_' . $j] < $currentMonth or $currentMonth < $ocData['oc_opentimes_until_month_' . $j])
        ) {

            $matchingDay = true;
        }


        // we are only interested in times in the currently active time span
        if ($matchingDay) {

            $fromHour1 = intval(substr($ocData['oc_' . $currentWeekday . '_from_' . $j . '_1'], 0, strpos($ocData['oc_' . $currentWeekday . '_from_' . $j . '_1'], ':')));
            $fromMinute1 = intval(substr($ocData['oc_' . $currentWeekday . '_from_' . $j . '_1'], strpos($ocData['oc_' . $currentWeekday . '_from_' . $j . '_1'], ':') + 1));

            $untilHour1 = intval(substr($ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'], 0, strpos($ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'], ':')));
            $untilMinute1 = intval(substr($ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'], strpos($ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'], ':') + 1));

            $fromHour2 = intval(substr($ocData['oc_' . $currentWeekday . '_from_' . $j . '_2'], 0, strpos($ocData['oc_' . $currentWeekday . '_from_' . $j . '_2'], ':')));
            $fromMinute2 = intval(substr($ocData['oc_' . $currentWeekday . '_from_' . $j . '_2'], strpos($ocData['oc_' . $currentWeekday . '_from_' . $j . '_2'], ':') + 1));

            $untilHour2 = intval(substr($ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'], 0, strpos($ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'], ':')));
            $untilMinute2 = intval(substr($ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'], strpos($ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'], ':') + 1));

            // only check for open times if day not marked as closed
            if (!isset($ocData['oc_closed_' . $currentWeekday . '_' . $j]) && !oc_checkHoliday($ocData)) {
                // straight forward case 1: current hour is between opening hours
                if (is_numeric($fromHour1) && $fromHour1 < $currentHour && $currentHour < $untilHour1) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'];
                } // straight forward case 2: current hour is between opening hours
                else if (is_numeric($fromHour2) && $fromHour2 < $currentHour && $currentHour < $untilHour2) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'];
                } // edge case 1: current hour is same as opening hour
                else if (is_numeric($fromHour1) && $fromHour1 == $currentHour && is_numeric($fromMinute1) && $fromMinute1 <= $currentMinute && is_numeric($untilHour1) && $currentHour < $untilHour1) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'];
                } // edge case 2: current hour is same as opening hour
                else if (is_numeric($fromHour2) && $fromHour2 == $currentHour && is_numeric($fromMinute2) && $fromMinute2 <= $currentMinute && is_numeric($untilHour2) && $currentHour < $untilHour2) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'];
                } // edge case 3: current hour is same as closing hour
                else if (is_numeric($fromHour1) && $fromHour1 < $currentHour && is_numeric($untilHour1) && $currentHour == $untilHour1 && is_numeric($untilMinute1) && $currentMinute <= $untilMinute1) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'];
                } // edge case 4: current hour is same as closing hour
                else if (is_numeric($fromHour2) && $fromHour2 < $currentHour && is_numeric($untilHour2) && $currentHour == $untilHour2 && is_numeric($untilMinute2) && $currentMinute <= $untilMinute2) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'];
                } // edge case 5: current hour is same as opening and closing hour
                else if (is_numeric($fromHour1) && $fromHour1 == $currentHour && is_numeric($fromMinute1) && $fromMinute1 <= $currentMinute && is_numeric($untilHour1) && $currentHour == $untilHour1 && is_numeric($untilMinute1) && $currentMinute <= $untilMinute1) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_1'];
                } // edge case 6: current hour is same as opening and closing hour
                else if (is_numeric($fromHour2) && $fromHour2 == $currentHour && is_numeric($fromMinute2) && $fromMinute2 <= $currentMinute && is_numeric($untilHour2) && $currentHour == $untilHour2 && is_numeric($untilMinute2) && $currentMinute <= $untilMinute2) {
                    $toReturn['closeTime'] = $ocData['oc_' . $currentWeekday . '_until_' . $j . '_2'];
                }
            }

            // shop is not open currently -> find next open time
            if (!$toReturn['closeTime']) {


                // check current day for upcoming opening times
                if (!isset($ocData['oc_closed_' . $currentWeekday . '_' . $j]) && !oc_checkHoliday($ocData)) {
                    if (($currentHour < $fromHour1) or ($currentHour == $fromHour1 and $currentMinute < $fromMinute1)) {

                        $toReturn['openTime'] = $ocData['oc_' . $currentWeekday . '_from_' . $j . '_1'];
                        $toReturn['openDate'] = $currentYear . '-' . date('m', current_time('timestamp')) . '-' . date('d', current_time('timestamp'));
                        $toReturn['openDateString'] = 'heutigen ' . $weekdays[$currentWeekday] . ', ';

                    } else if (($currentHour < $fromHour2) or ($currentHour == $fromHour2 && $currentMinute < $fromMinute2)) {

                        $toReturn['openTime'] = $ocData['oc_' . $currentWeekday . '_from_' . $j . '_2'];
                        $toReturn['openDate'] = $currentYear . '-' . date('m', current_time('timestamp')) . '-' . date('d', current_time('timestamp'));
                        $toReturn['openDateString'] = 'heutigen ' . $weekdays[$currentWeekday] . ', ';
                    }
                }

                // check next 21 days for upcoming opening times

                // if currently closed?
                if (!$toReturn['openTime']) {
                    //get the courrent weekday as number
                    $baseIndex = array_search($currentWeekday, $weekdayAssoc);

                    // for the next 20 Days
                    for ($k = 1; $k <= 20; $k++) {
                        $weekdayToCheck = $weekdayAssoc[$k + $baseIndex];

                        $weekdayResult = oc_checkWeekday($ocData, $weekdayToCheck, $j, $k);
                        if ($weekdayResult['openTime']) {
                            if ($k < 7) { // diese Woche Offen?
                                $toReturn = $weekdayResult;
                            } else { // diese Woche nicht mehr offen
                                $weekdayResult['openDateString'] = $weekdayResult['openDateString'] . ' den ' . date('d.m.', strtotime($weekdayResult['openDate']));
                                $toReturn = $weekdayResult;
                            }
                            break;
                        }
                    }
                }
            }
        }
    }
    return $toReturn;
}

function oc_add_esitags($filename, $shortcode, $content = null, $is_callback = false){
    if ( ! $is_callback ){
        $esi  = "\n";
        $esi .= '<!--esi ' . "\n";
        $esi .= '   <esi:include src="' . OC_PLUGIN_ESI_URL  . $filename . '.php?name=' . $shortcode . '"/>' . "\n";
        $esi .= '-->' . "\n";
        $esi .= '<esi:remove>' . "\n";
        $esi .= $content . "\n";
        $esi .= '</esi:remove>' . "\n";
        $content = $esi;
    }

    return $content;
}

// retrieves the attachment ID from the file URL
function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
    return isset($attachment[0]) ? $attachment[0] : FALSE;
}
