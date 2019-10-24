<?php
/**
 * @author donknap
 * @date 18-7-18 下午5:41
 */

return [
	'common' => [
		'pid_file' => '/tmp/swoole.pid',
		'pname' => "w7-swoole",
		'max_request' => 10000,
		'daemonize' => 0,
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
		'port'  => ienv('SERVER_HTTP_PORT', 88)
	],
];
