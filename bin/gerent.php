#!/usr/bin/env php
<?php
/**
 * 辅助命令控制台
 * @author donknap
 * @date 18-7-18 下午6:35
 */

require_once dirname(__DIR__) . '/config/define.php';
require_once BASE_PATH . '/vendor/autoload.php';


$_SERVER['argv'] = [
	$argv[0],
	'gerent',
	$argv[1]
];
$argv = null;


$app = \W7\App::getApp();
$app->runConsole();