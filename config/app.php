<?php

return [
	'setting' => [
		'development' => '1', //开启后，每次启动服务会清空日志，程序运行时会显示代码错误
	],
	'reload' => [
		'enabled' => true, //是否开启自动监测文件变化重载swoole服务
		'interval' => 5, //重复检测的间隔时长
		'debug' => true, //开启后，将不监控文件变化，重复reload，方便调试
	],
	'cache' => [
		'memory' => [
			'size' => 10240,
			'name' => 'test',
		],
		'redis'=>[
			"redis_url" => "39.105.18.200:6350/1?auth=%5E5JL%21%21KJ%40%40eaOcJ%29",
			"timeout" => 10,
			'prefix'=>'',
		],
	],
	'database' => [
		'default' => [
			'driver' => 'mysql',
			'database' => 'we7_api',
			'host' => '172.16.1.13',
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => 'ims_',
			'port' =>'3306',
		],
		'addons' => [
			'driver' => 'mysql',
			'read' => [
				'host' => ['172.16.1.152'],
			],
			'write' => [
				'host' => '172.16.1.12'
			],
			'database' => 'we7_addons_api',
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => 'ims_',
			'port'=>'3306',
		],
	],
	'pool' => [
		'database' => [
			'172.16.1.152' => [
				'enable' => true,
				'max' => 1000,
			],
			'172.16.1.12' => [
				'enable' => true,
				'max' => 20,
			],
		],
		'cache' => [
			'redis' => [

			],
		]
	],
	'wchat' => [
		'appid'=>'',
		'redirect_uri'=>'',
		'scope'=>'',
	],
	'middleware' => [
		'before' => [],
		'after' => [],
	],
	'event' => [
		'beforeStart'=>[

		],
		'beforeRequest'=>[

		],
		'afterRequest'=>[

		],
	]
];