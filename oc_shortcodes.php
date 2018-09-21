<?php
/**
 * Register all needed shortcodes dynamically
 */
add_action('init', 'oc_addShortcodes');
function oc_addShortcodes() {
    for ($shopId = 1; $shopId <= OC_MAX_SHOPS_NUMBER; $shopId++) {
        $ocDataRaw = get_option('oc_shop_'.$shopId);
        $ocData = unserialize($ocDataRaw);
        if (isset($ocData['oc_shortcode_'.$shopId]) && $ocData['oc_shortcode_'.$shopId] != '') {
            add_shortcode($ocData['oc_shortcode_'.$shopId], 'oc_snippet');
            add_shortcode($ocData['oc_shortcode_'.$shopId].'_sign', 'oc_sign');
            add_shortcode($ocData['oc_shortcode_'.$shopId].'_overview', 'oc_snippet_overview');
            add_shortcode($ocData['oc_shortcode_'.$shopId].'_overview_sommer', 'oc_snippet_overview_sommer');
            add_shortcode($ocData['oc_shortcode_'.$shopId].'_overview_winter', 'oc_snippet_overview_winter');
        }
    }
}

/**
 * Main Shortcode routine
 *
 * Show Shortcodes like [oc_shopname]
 * Displays the status of the current day and if closed, when the next opening time is.
 *
 * @param $attr
 * @param bool $content
 * @param string $code
 * @return mixed|string
 */
function oc_snippet($attr, $content=false, $code="") {
    $openText = '';
    $openLink = '';
    $openFrontendText = '';
    $closedText = '';
    $closedLink = '';
    $closedFrontendText = '';
    $ocData = array();

    $holiday = false;
    $extraDate = false;
    $normalDate = false;

    //for each awayible shop
    for ($shopId = 1; $shopId <= OC_MAX_SHOPS_NUMBER; $shopId++) {
        //get the Shopdata
        $ocDataRaw = get_option('oc_shop_'.$shopId);

        $ocData = unserialize($ocDataRaw);

        // if current array is the requested store
        if ($ocData['oc_shortcode_'.$shopId] == $code) {
            $openText = $ocData['oc_open_text'];
            $openLink = $ocData['oc_open_link'];
            $openFrontendText = $ocData['oc_open_frontend_text'];
            $closedText = $ocData['oc_closed_text'];
            $closedLink = $ocData['oc_closed_link'];
            $closedFrontendText = $ocData['oc_closed_frontend_text'];

            $ocData['oc_current_store_id'] = $shopId;

            $extraDate = oc_checkExtraDates($ocData);
            $ocData['wasSpecial'] = $extraDate['wasSpecial'];
            $holiday = oc_checkHoliday($ocData);
            $normalDate = oc_checkStandardDates($ocData);
            break;
        }
    }
    $day_next_open = '';
    $open_time = '';
    $close_time = '';

    $toReturn = '';

    // we have an extra date
    if ($extraDate and $extraDate['closeTime']) {

        $openText = $ocData['oc_special_days'][$extraDate['extraIndex']]['text'];
        $openLink = $ocData['oc_special_days'][$extraDate['extraIndex']]['link'];

        if ($extraDate['currentlySpecial']){

            if( ! empty( $openLink ) ){
                $toReturn = $openFrontendText;
            } else {
                $toReturn = $openFrontendText;
            }
            $close_time = $extraDate['closeTime'];

        } else {

            if ($extraDate['openDate'] <= $normalDate['openDate'] || empty( $normalDate['openDate'] )) {

                $toReturn = $closedFrontendText;
                $day_next_open = $extraDate['openDateString'];
                $open_time = $extraDate['openTime'];

            } else {

                $toReturn = $closedFrontendText;
                $day_next_open = $normalDate['openDateString'];
                $open_time = $normalDate['openTime'];

            }

        }

    //currently is a normal date and not a holiday
    } else if ($normalDate and $normalDate['closeTime'] and !$holiday) {

        if ($extraDate['wasSpecial']){

            $toReturn = $closedFrontendText;
            $day_next_open = $extraDate['openDateString'];
            $open_time = $extraDate['openTime'];

        } else {

            $toReturn = $openFrontendText;
            $close_time = $normalDate['closeTime'];

        }

    } else {

        if ($extraDate and $normalDate and (!empty($extraDate['openDate']) || !empty($extraDate['closeDate'])) and $extraDate['openDate'] <= $normalDate['openDate']) {

            $toReturn = $closedFrontendText;
            $day_next_open = $extraDate['openDateString'];
            $open_time = $extraDate['openTime'];

        } else if ($normalDate) {

            $toReturn = $closedFrontendText;
            if ( !empty($extraDate['openDateString']) && empty($normalDate['openDateString']) ){
                $day_next_open = $extraDate['openDateString'];
                $open_time = $extraDate['openTime'];
            } else{
                $day_next_open = $normalDate['openDateString'];
                $open_time = $normalDate['openTime'];
            }

        }
    }
    $search_array  = array(
        '{LinktextOpen}',
        '{LinktextClosed}',
        '{DayNextOpen}',
        '{OpenTime}',
        '{CloseTime}',
        '{sign}'
    );
    $replace_array = array(
        '<a href="'.$openLink.'">'.$openText.'</a>',
        '<a href="'.$closedLink.'">'.$closedText.'</a>',
        $day_next_open,
        $open_time,
        $close_time,
        do_shortcode('['. $code .'_sign]')
    );
    $toReturn = str_replace($search_array,$replace_array, $toReturn);
    if ( empty( $ocData['oc_caching']['live'] ) ){
        return $toReturn;
    } else {
        return oc_add_esitags('snippet', $code, $toReturn, array_key_exists( 'is_callback', (array) $attr ) );
    }
}

/**
 * Open / Closed sign Shortcode
 *
 * Show Shortcodes like [oc_shopname_]
 * Displays the status of the current day and if closed, when the next opening time is.
 *
 * @param $attr
 * @param bool $content
 * @param string $code
 * @return mixed|string
 */
function oc_sign($attr, $content=false, $code="") {
    global $default_image;

    $holiday = false;
    $extraDate = false;
    $normalDate = false;
    $sign_status = 'open';
    $ocData = array();

    //for each awayible shop
    for ($shopId = 1; $shopId <= OC_MAX_SHOPS_NUMBER; $shopId++) {
        //get the Shopdata
        $ocDataRaw = get_option('oc_shop_'.$shopId);

        $ocData = unserialize($ocDataRaw);

        // if current array is the requested store
        if ($ocData['oc_shortcode_'.$shopId] . '_sign' == $code) {
            $ocData['oc_current_store_id'] = $shopId;

            $extraDate = oc_checkExtraDates($ocData);
            $ocData['wasSpecial'] = $extraDate['wasSpecial'];
            $holiday = oc_checkHoliday($ocData);
            $normalDate = oc_checkStandardDates($ocData);
            break;
        }
    }

    // we have an extra date
    if ($extraDate and $extraDate['closeTime']) {

        //currently is closed
        if (! $extraDate['currentlySpecial']){
            //change to closed
            $sign_status = 'closed';
        }
    //currently is a normal date and not a holiday
    } else if ($normalDate and $normalDate['closeTime'] and !$holiday) {

        // currently is closed
        if ($extraDate['wasSpecial']){
            $sign_status = 'closed';
        }
    } else {
        if ($extraDate and $normalDate and (!empty($extraDate['openDate']) || !empty($extraDate['closeDate'])) and $extraDate['openDate'] <= $normalDate['openDate']) {

            //change to closed
            $sign_status = 'closed';

        } else if ($normalDate) {

            //change to closed
            $sign_status = 'closed';

        }
    }

    if ( isset( $ocData['oc_images'][$sign_status] ) ) {

        if (! in_array($ocData['oc_images'][$sign_status], $default_image)){
            $sign_id = pippin_get_image_id($ocData['oc_images'][$sign_status]);
            if ($sign_id){
                $sign = wp_get_attachment_image( $sign_id, array(24,16) );
            } else {
                $sign = sprintf(
                    '<img width="24px" height="16px" src="%s" alt="%s">',
                    $default_image['neutral'],
                    $sign_status
                );
            }
        } else {
            $sign = sprintf(
                '<img width="24px" height="16px" src="%s" alt="%s">',
                $ocData['oc_images'][$sign_status],
                $sign_status
            );
        }

    } else {
        if ($sign_status == 'open') {
            $sign = '<!-- offen -->';
        } else{
            $sign = '<!-- zu -->';
        }
    }

    if ( empty($ocData['oc_caching']['live']) ){
        return $sign;
    } else {
	    return oc_add_esitags('snippet', $code, $sign, array_key_exists( 'is_callback', (array) $attr ) );
    }
}


/* * * * * * * * * * * * * * * *
 *  OVERVIEWS
 * * * * * * * * * * * * * * * */

/**
 * Show current Overview listing
 *
 * Show Shortcode like [oc_shopname_overview]
 *
 * @param $attr
 * @param mixed|bool $content
 * @param string $code
 * @return string
 */
function oc_snippet_overview($attr, $content=false, $code="") {
	
	$toReturn = '';
	
	for ($shopId = 1; $shopId <= OC_MAX_SHOPS_NUMBER; $shopId++) {
		$ocDataRaw = get_option('oc_shop_'.$shopId);
		$ocData = unserialize($ocDataRaw);
		if ($ocData['oc_shortcode_'.$shopId].'_overview' == $code) {
			$toReturn = oc_getOpenTimes($ocData);
			break;
		}
	}

    if ( empty($ocData['oc_caching']['longterm']) ){
        return $toReturn;
    } else {
	    return oc_add_esitags('overview', $code, $toReturn, array_key_exists( 'is_callback', (array) $attr ) );
    }
}

/**
 * Show specific summer Overview listing
 *
 * Show Shortcode like [oc_shopname_overview_sommer]
 *
 * @param $attr
 * @param bool $content
 * @param string $code
 * @return string
 */
function oc_snippet_overview_sommer($attr, $content=false, $code="") {
	
	$toReturn = '';
	
	for ($shopId = 1; $shopId <= OC_MAX_SHOPS_NUMBER; $shopId++) {
		$ocDataRaw = get_option('oc_shop_'.$shopId);
		$ocData = unserialize($ocDataRaw);
		if ($ocData['oc_shortcode_'.$shopId].'_overview_sommer' == $code) {
			
			$toReturn = oc_getOpenTimesFixed($ocData, 1);

			break;
		}
	}

    if ( empty($ocData['oc_caching']['longterm']) ){
        return $toReturn;
    } else {
	    return oc_add_esitags('overview', $code, $toReturn, array_key_exists( 'is_callback', (array) $attr ) );
    }
}

/**
 * Show specific winter Overview listing
 *
 * Show Shortcode like [oc_shopname_overview_winter]
 *
 * @param $attr
 * @param bool $content
 * @param string $code
 * @return string
 */
function oc_snippet_overview_winter($attr, $content=false, $code="") {
	
	$toReturn = '';
	
	for ($shopId = 1; $shopId <= OC_MAX_SHOPS_NUMBER; $shopId++) {
		$ocDataRaw = get_option('oc_shop_'.$shopId);
		$ocData = unserialize($ocDataRaw);
		if ($ocData['oc_shortcode_'.$shopId].'_overview_winter' == $code) {
			
			$toReturn = oc_getOpenTimesFixed($ocData, 2);
			break;
		}
	}

    if ( empty($ocData['oc_caching']['longterm']) ){
        return $toReturn;
    } else {
	    return oc_add_esitags('overview', $code, $toReturn, array_key_exists( 'is_callback', (array) $attr ) );
    }
}