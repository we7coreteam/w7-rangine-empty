<?php

/**
 * WeEngine Api System
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

require_once dirname(__DIR__, 1) . '/config/define.php';
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

$server = new \W7\Fpm\Server\Server();
new \W7\App();
$server->start();
