<?php

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
new \W7\App();
$server = new \W7\Fpm\Server\Server();
$server->start();
