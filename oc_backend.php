<?php

/**
 * Enqueue the date picker
 */
function oc_enqueue_date_picker(){
    if (isset($_GET['page']) && $_GET['page'] == 'oc-toplevel') {
        wp_enqueue_media();

        wp_enqueue_script(
            'oc-scripts-js',
            plugin_dir_url( __FILE__ ) . 'views/assets/oc_scripts.js',
            array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
            OC_VERSION,
            true
        );

        wp_enqueue_style( 'jquery-ui-datepicker' );
        wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_style(
            'oc-styles-css',
            plugin_dir_url( __FILE__ ) . 'views/assets/oc_styles.css',
            null,
            OC_VERSION
        );
    }
}
add_action( 'admin_enqueue_scripts', 'oc_enqueue_date_picker' );

/**
 * add backend pages
 */
function oc_addPage() {
    add_menu_page('Open Closed', 'OpenClosed', 'manage_options', 'oc-toplevel', 'oc_page_main','dashicons-clock');
}


function oc_page_main() {
    global $default_image;
    // Also adds a check to make sure `wp_enqueue_media` has only been called once.
    // @see: http://core.trac.wordpress.org/ticket/22843
    if ( ! did_action( 'wp_enqueue_media' ) )
        wp_enqueue_media();

    if (isset($_GET['shop'])) {
        $shopId = intval($_GET['shop']);
    } else {
        $shopId = 1;
    }

    $days = array('-', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);
    $weekdays = array(
        'mon' => 'Montag',
        'tue' => 'Dienstag',
        'wed' => 'Mittwoch',
        'thu' => 'Donnerstag',
        'fri' => 'Freitag',
        'sat' => 'Samstag',
        'sun' => 'Sonntag');
    $months = array('-', 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    $years = array(0, 1);

    //Setup timing dropdown array
    $times = array();
    $times[] = '--:--';
    for ($i = 5; $i < 24; $i++) {
        $times[] = $i . ':00';
        $times[] = $i . ':15';
        $times[] = $i . ':30';
        $times[] = $i . ':45';
    }
    $times[] = '23:59';

    // include form saving functions
    if ($_POST) {

        unset($_POST['oc_special_days']['X']);

        $openClosedArr = array();
        $allowedValues = array();


        $allowedValues[] = 'oc_shopname';
        $allowedValues[] = 'oc_open_text';
        $allowedValues[] = 'oc_open_link';
        $allowedValues[] = 'oc_closed_text';
        $allowedValues[] = 'oc_closed_link';
        $allowedValues[] = 'oc_open_frontend_text';
        $allowedValues[] = 'oc_closed_frontend_text';

        $holidaysFiles  = ( isset( $_FILES['oc_holidays'] ) )       ? $_FILES['oc_holidays']       : array();
        $manualHolidays = ( isset( $_POST['oc_holidays_manual'] ) ) ? $_POST['oc_holidays_manual'] : array();
        $holidays = false;


        /**
         * Check if Holiday ical File was uploaded, or additional dates are entered.
         */
        if ( ( !empty( $holidaysFiles['name'][0] ) ) || ! empty( $manualHolidays[0]['date'] ) ) {

            $events = array();
            if( ( $holidaysFiles &&  !empty( $holidaysFiles['name'][0] ) ) ) {
                $uploads = wp_upload_dir();
                $targetFile = $uploads['path'] . '/' . $holidaysFiles['name'][0];

                move_uploaded_file($holidaysFiles['tmp_name'][0], $targetFile);

                require_once( plugin_dir_path( __FILE__ ) . 'helpers/icalReader.class.php' );

                $ical = new ICal($targetFile);
                $events = $ical->events();
            }

            if( ! empty( $manualHolidays[0]['date'] ) ) {
                $holiday_addition = array();
                foreach ($manualHolidays as $manual){
                    list($day, $month, $year) = explode('.',$manual['date']);
                    $holiday_addition[] = array( 'DTSTART' => $year . $month . $day, 'SUMMARY' => $manual['name']);
                }

                $events = array_merge($events,$holiday_addition);
            }

            $holidays = array();

            foreach ($events as $holiday) {

                if (strpos($holiday['DTSTART'],'-') === false) {
                    $localYear = substr($holiday['DTSTART'], 0, 4);
                    $localMonth = substr($holiday['DTSTART'], 4, 2);
                    $localDay = substr($holiday['DTSTART'], 6, 2);

                    $localDate = $localYear . '-' . $localMonth . '-' . $localDay;

                } else {
                    $localDate = $holiday['DTSTART'];
                }

                $allowedValues[] = 'oc_holiday_' . $localDate;

                $holidays[$localDate] = $holiday['SUMMARY'];
            }

        }


        /**
         * Special Day Handling starts here
         */
        for ($extraDate = 1; $extraDate <= OC_MAX_EXTRA_DATES; $extraDate++) {
            $allowedValues[] = 'oc_special_days';
            $allowedValues[] = 'oc_extratime_from_' . $extraDate;
            $allowedValues[] = 'oc_extratime_until_' . $extraDate;
            $allowedValues[] = 'oc_extra_text_' . $extraDate;
            $allowedValues[] = 'oc_extra_link_' . $extraDate;
        }

        $c = date('Y', current_time('timestamp'));
        for ($y = $c; $y <= $c + 2; $y++) {
            //for this and the next 2 years
            for ($m = 1; $m <= 12; $m++) {
                // for every Month
                for ($d = 1; $d <= 31; $d++) {
                    // for every day
                    $holidayKey = $y . '-' . str_pad($m, 2, 0, STR_PAD_LEFT) . '-' . str_pad($d, 2, 0, STR_PAD_LEFT);
                    $allowedValues[] = 'oc_holiday_' . $holidayKey;
                }
            }
        }


        for ($j = 1; $j <= 2; $j++) {
            $allowedValues[] = 'oc_opentimes_from_day_' . $j;
            $allowedValues[] = 'oc_opentimes_from_month_' . $j;
            $allowedValues[] = 'oc_opentimes_until_day_' . $j;
            $allowedValues[] = 'oc_opentimes_until_month_' . $j;

            foreach ($weekdays as $key => $weekday) {
                $allowedValues[] = 'oc_' . $key . '_from_' . $j . '_1';
                $allowedValues[] = 'oc_' . $key . '_until_' . $j . '_1';
                $allowedValues[] = 'oc_' . $key . '_from_' . $j . '_2';
                $allowedValues[] = 'oc_' . $key . '_until_' . $j . '_2';
                $allowedValues[] = 'oc_closed_' . $key . '_' . $j;
            }
        }

        if ($_POST['oc_shopname']) {
            $openClosedArr['oc_shortcode_' . $shopId] = 'oc_' . oc_convertToAscii(oc_convertUmlaute(strtolower($_POST['oc_shopname'])));
        }
        $allowedValues[] = 'oc_images';
        $allowedValues[] = 'oc_caching';

        foreach ($allowedValues as $allowed) {

            if (isset($_POST[$allowed])) {
                $openClosedArr[$allowed] = $_POST[$allowed];

            } else if (substr($allowed, 0, 11) == 'oc_holiday_') {
                if (isset($holidays[substr($allowed, 11, 10)])) {
                    $openClosedArr[$allowed] = $holidays[substr($allowed, 11, 10)];
                }
            }
        }

        $openClosedArrFlat = serialize($openClosedArr);
        $ocData = $openClosedArr;

        update_option('oc_shop_' . $shopId, $openClosedArrFlat);

    } else {
        $ocDataRaw = get_option('oc_shop_' . $shopId);
        $ocData = unserialize($ocDataRaw);
    }

    /**
     * Render the Admin page
     */
    require_once( 'views/mainpage.php' );
}

// Hook for adding admin menus
add_action('admin_menu', 'oc_addPage');