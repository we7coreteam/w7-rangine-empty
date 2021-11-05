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

return [
	'setting' => [
		'env' => ienv('SETTING_DEVELOPMENT', RELEASE),
		'error_reporting' => ienv('SETTING_ERROR_REPORTING', E_ALL),
		'server' => ienv('SETTING_SERVERS', 'http'),
		'basedir' => [
			BASE_PATH, //在测试用例中需要，正式项目中删除
			BASE_PATH . '/tests',
			BASE_PATH . '/../w7-rangine',
			BASE_PATH . '/../w7-rangine-http-message'
		],
		'file_ignore' => [],
		'lang' => ienv('APP_LOCAL', 'zh_CN')
	],
	'session' => [
		'name' => ienv('SESSION_NAME', session_name()),
		'expires' => ienv('SESSION_EXPIRES', 0),
		'handler' => ienv('SESSION_HANDLER', 'file'),
		'save_path' => ienv('SESSION_FILE_HANDLER_SAVE_PATH', '/tmp/session'),
		'auto_start' => ienv('SESSION_AUTO_START', 1)
	],
	'cookie' => [
		'path' => ienv('COOKIE_PATH', '/'),
		'http_only' => ienv('COOKIE_HTTP_ONLY', false),
		'domain' => ienv('COOKIE_DOMAIN', ''),
		'secure' => ienv('COOKIE_SECURE', false),
		'same_site' => ienv('COOKIE_SAME_SITE', ''),
	],
	'cache' => [
		'default' => [
			'driver' => ienv('CACHE_DEFAULT_DRIVER', 'redis'),
			'host' => ienv('CACHE_DEFAULT_HOST', '127.0.0.1'),
			'port' => ienv('CACHE_DEFAULT_PORT', '6379'),
			'password' => ienv('CACHE_DEFAULT_PASSWORD', ''),
			'timeout' => ienv('CACHE_DEFAULT_TIMEOUT', '30'),
			'database' => ienv('CACHE_DEFAULT_DATABASE', '0')
		]
	],
	'database' => [
		'default' => [
			'driver' => 'mysql',
			'database' => ienv('DATABASE_DEFAULT_DATABASE', ''),
			'host' => ienv('DATABASE_DEFAULT_HOST', ''),
			'username' => ienv('DATABASE_DEFAULT_USERNAME', ''),
			'password' => ienv('DATABASE_DEFAULT_PASSWORD', ''),
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => 'ims_',
			'port' => '3306',
			'strict' => false,
		]
	],
	'pool' => [
		'database' => [
			'default' => [
				'enable' => ienv('POOL_DATABASE_DEFAULT_ENABLE', false),
				'max' => ienv('POOL_DATABASE_DEFAULT_MAX', 20)
			]
		],
		'cache' => [
			'default' => [
				'enable' => ienv('POOL_CACHE_DEFAULT_ENABLE', false),
				'max' => ienv('POOL_CACHE_DEFAULT_MAX', 20)
			]
		]
	]
];
