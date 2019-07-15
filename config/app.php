<?php

return [
	'setting' => [
		//SETTING_DEVELOPMENT = DEVELOPMENT^CLEAR_LOG
		//SETTING_DEVELOPMENT = DEBUG|CLEAR_LOG
		//SETTING_DEVELOPMENT = RELEASE|CLEAR_LOG
		'env' => ienv('SETTING_DEVELOPMENT', RELEASE),
		//最新版可用
		'error_level' => E_ALL ^ E_NOTICE,
		'basedir' => [
			'/home/wwwroot/we7/swoole'
		]
	],
	'crontab' => [
		'enabled' => false,
		'interval' => 10,
	],
	'reload' => [
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
				'enable' => true,
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
			'enable' => ienv('PROCESS_ENCRYPT_ENABLE', false),
			'class' => \W7\App\Process\EncryptProcess::class,
			'number' => ienv('PROCESS_ENCRYPT_NUMBER', 1),
		]
	],
];
