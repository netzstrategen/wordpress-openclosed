<?php

$ttl = 5 * 60;
header('Expires: ' . date('r', time() + $ttl));
header('Cache-Control: max-age=' . $ttl);

require_once dirname(__FILE__) . '/esi_function.php';
