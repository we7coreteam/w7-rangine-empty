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
	'session' => [
		//自定义session handler, 在app/Handler/Session/TestHandler
		'handler' => 'test'
	],
	//如果session channel为cookie时可添加以下配置
	'cookie' => [
		'path' => ienv('SESSION_PATH', '/'),
		'http_only' => ienv('SESSION_HTTP_ONLY', false),
		'domain' => ienv('SESSION_DOMAIN', ''),
		'secure' => ienv('SESSION_SECURE', false),
		'expires' => ienv('SESSION_EXPIRES', 0),//不设置，默认取session.gc_maxlifetime配置
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
		'test' => [
			//自定义handler  在app/Handler/Log/TestHandler
			'driver' => 'test'
		],
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
		'encrypt' => [
			'enable' => ienv('PROCESS_ENCRYPT_ENABLE', false),
			'class' => \W7\App\Process\EncryptProcess::class,
			'number' => ienv('PROCESS_ENCRYPT_NUMBER', 1),
		]
	],
];
