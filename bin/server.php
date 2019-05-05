#!/usr/bin/env php
<?php
/**
 * 服务启动管理
 * @author donknap
 * @date 18-7-18 下午6:35
 */

require_once dirname(__DIR__) . '/config/define.php';
require_once BASE_PATH . '/vendor/autoload.php';


$_SERVER['argv'] = array_merge(
	[
		array_shift($argv),
		array_shift($argv) . ':' . array_shift($argv)
	],
	$argv
);

$app = \W7\App::getApp();
$app->runConsole();
