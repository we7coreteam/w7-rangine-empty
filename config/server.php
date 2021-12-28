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

use W7\App\Handler\Mqtt\MessageHandler;

return [
	'common' => [
		'pid_file' => '/tmp/swoole.pid',
		'max_request' => 10000,
		'worker_num' => ienv('SERVER_COMMON_WORKER_NUM', 2),
		'task_worker_num' => ienv('SERVER_COMMON_TASK_WORKER_NUM', 1),
		'package_max_length' => ienv('SERVER_COMMON_PACKAGE_MAX_LENGTH', 5242880), // 5M
		'buffer_output_size' => ienv('SERVER_COMMON_BUFFER_MAX_LENGTH', 10485760), // 10*1024*1024
	],
	'tcp' => [
		'host'  => '0.0.0.0',
		'port'  => ienv('SERVER_TCP_PORT', 8888)
	],
	'http' => [
		'host'  => '0.0.0.0',
		'port'  => ienv('SERVER_HTTP_PORT', 888)
	],
	'webSocket' => [
		'host'  => '0.0.0.0',
		'port'  => ienv('SERVER_WEBSOCKET_PORT', 888)
	],
	'mqtt' => [
		'host'  => '0.0.0.0',
		'port'  => ienv('SERVER_WEBSOCKET_PORT', 889),
		'handler' => MessageHandler::class
	]
];
