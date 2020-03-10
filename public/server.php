<?php

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';
$script = __FILE__;

$argv = [];

$_SERVER['argv'] = array_merge(
	[
		$script,
		'server:start',
		'--config-app-setting-server=fpm'
	],
	$argv
);

$app = new \W7\App();
$app->runConsole();