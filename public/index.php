<?php
require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
$server = new \W7\Fpm\Server\Server();
new \W7\App();
$server->start();
