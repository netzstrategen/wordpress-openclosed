<?php
/*
  Plugin Name: Open Closed
  Version: 1.0.2
  Description: Show business opening hours and inform about next open day.
  Author: netzstrategen
  Author URI: https://netzstrategen.com
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0
*/
defined("OC_VERSION")        or define('OC_VERSION', "1.0.2");
defined("OC_PLUGIN_PATH")    or define('OC_PLUGIN_PATH', plugin_dir_path(__FILE__));
defined("OC_PLUGIN_URL")     or define('OC_PLUGIN_URL', plugin_dir_url(__FILE__));
defined("OC_PLUGIN_ESI_URL") or define('OC_PLUGIN_ESI_URL', str_replace('https', 'http', OC_PLUGIN_URL). 'views/esi/');

$plugin_key = 'openclosed';

$default_image['neutral'] = OC_PLUGIN_URL . 'views/assets/icon-times.png';
$default_image['open']    = OC_PLUGIN_URL . 'views/assets/icon-times-open.png';
$default_image['closed']   = OC_PLUGIN_URL . 'views/assets/icon-times-closed.png';

// helper functions outsourced
require_once( OC_PLUGIN_PATH . 'helpers/oc_helpers.php' );
require_once( OC_PLUGIN_PATH  . 'helpers/oc_functions.php' );

// functions for general open times overviews outsourced
require_once( OC_PLUGIN_PATH . 'oc_shortcodes.php' );

//admin pages
if ( is_admin() ){
    require_once('oc_backend.php');
}
