<?php
return [
	'setting' => [
		//SETTING_DEVELOPMENT = DEVELOPMENT^CLEAR_LOG
		//SETTING_DEVELOPMENT = DEBUG|CLEAR_LOG
		//SETTING_DEVELOPMENT = RELEASE|CLEAR_LOG
		'env' => ienv('SETTING_DEVELOPMENT', RELEASE),
		//最新版可用
		'error_reporting' => ienv('SETTING_ERROR_REPORTING', E_ALL),
		'basedir' => [
			BASE_PATH, //在测试用例中需要，正式项目中删除
			'/home/wwwroot/we7/swoole',
			BASE_PATH . '/tests',
		],
		'lang' => 'zh-CN'
	],
	'cache' => [
		'default' => [
			'driver' => 'redis',
			'host' => '127.0.0.1',
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
		]
	],
	'pool' => [
		'database' => [
			'default' => [
				'enable' => true,
				'max' => 1000,
			]
		],
		'cache' => [
			'redis' => [
				'enable' => false,
				'max' => 20,
			]
		]
	]
];
