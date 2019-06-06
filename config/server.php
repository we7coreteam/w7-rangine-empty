<?php
/**
 * @author donknap
 * @date 18-7-18 下午5:41
 */

$serverSetting = [
	'common' => [
		'pid_file' => '/tmp/swoole.pid',
		'pname' => "w7-swoole",
		'worker_num' => 1,
		'max_request' => 10000,
		'daemonize' => 0,
		'dispatch_mode' => 3,
		'log_file' => BASE_PATH . '/runtime/logs/run.log',
		'log_level' => 0,
		'task_worker_num' => 1,
		'package_max_length' => 5242880, // 5M
		'buffer_output_size' => 10485760, // 10*1024*1024
		'upload_tmp_dir' => BASE_PATH . '/runtime/upload',
// 		'document_root' => BASE_PATH . '/public',
		'enable_static_handler' => true,
		'ssl_cert_file' => '',
		'ssl_key_file' => '',
		'task_ipc_mode' => 2,
		'message_queue_key' => 0x70001001,
		'task_tmpdir' => BASE_PATH . '/runtime/task',
		'open_http2_protocol' => false

	],
	'tcp' => [
		'host'  => '0.0.0.0',
		'port'  => 8888,
		'mode' => SWOOLE_PROCESS,
		'sock_type'  => SWOOLE_SOCK_TCP,
	],
	'http' => [
		'host'  => '0.0.0.0',
		'port'  => 88,
		'mode' => SWOOLE_PROCESS,
		'sock_type'  => SWOOLE_SOCK_TCP,
	],
];

return $serverSetting;
