<?php
$path = explode('wp-content', dirname(__FILE__));


require_once($path[0] . 'wp-load.php');

if ( array_key_exists( 'name', $_GET ) && !empty( $_GET['name'] ) ){
	echo do_shortcode('[' . $_GET['name'] . ' is_callback=true]');
}