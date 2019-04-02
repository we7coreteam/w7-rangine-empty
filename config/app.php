<?php

return [
	'setting' => [
		'development' => '1', //开启后，每次启动服务会清空日志，程序运行时会显示代码错误
	],
	'crontab' => [
		'enabled' => false,
		'interval' => 10,
	],
	'reload' => [
		'enabled' => true, //是否开启自动监测文件变化重载swoole服务
		'interval' => 5, //重复检测的间隔时长
		'debug' => false, //开启后，将不监控文件变化，重复reload，方便调试
	],
	'cache' => [
		'default' => [
			'driver' => 'redis',
			'host' => '',
			'port' => '6379',
			'timeout' => 30,
		],
		'addons' => [ //可定义多个通道
			'driver' => 'redis',
			'host' => '',
			'port' => '6379',
			'timeout' => 30,
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
			'default' => [
				'enable' => false,
				'max' => 1000,
			],
			'addons' => [
				'enable' => false,
				'max' => 20,
			],
		],
		'cache' => [
			'redis' => [
				'enable' => false,
				'max' => 20,
			],
		]
	],
	'process' => [
		'encrypt' => [
			'enable' => false,
			'class' => \W7\App\Process\EncryptProcess::class,
		]
	],
];