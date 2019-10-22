<?php
/**
 * @author donknap
 * @date 18-7-18 下午5:41
 */

$serverSetting = [
	'common' => [
		'pid_file' => '/tmp/swoole.pid',
		'pname' => "w7-swoole",
		'max_request' => 10000,
		'daemonize' => 0,
		'task_worker_num' => 1,
		'package_max_length' => 5242880, // 5M
		'buffer_output_size' => 10485760, // 10*1024*1024
	],
	'tcp' => [
		'host'  => '0.0.0.0',
		'port'  => 8888
	],
	'http' => [
		'host'  => '0.0.0.0',
		'port'  => 88
	],
];

return $serverSetting;
