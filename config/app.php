<?php
return [
	'setting' => [
		//SETTING_DEVELOPMENT = DEVELOPMENT^CLEAR_LOG
		//SETTING_DEVELOPMENT = DEBUG|CLEAR_LOG
		//SETTING_DEVELOPMENT = RELEASE|CLEAR_LOG
		'env' => ienv('SETTING_DEVELOPMENT', RELEASE),
		//最新版可用
		'error_reporting' => E_ALL,
		'basedir' => [
			'/home/wwwroot/we7/swoole',
			BASE_PATH . '/tests',
		],
		'lang' => 'zh-CN'
	],
	'crontab' => [
		'enabled' => false,
		'interval' => 10,
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
		]
	],
	'database' => [
		'default' => [
			'driver' => 'mysql',
			'database' => 'default',
			'host' => '127.0.0.1',
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
				'host' => ['127.0.0.1'],
			],
			'write' => [
				'host' => '127.0.0.1'
			],
			'database' => 'addons',
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

	]
];
