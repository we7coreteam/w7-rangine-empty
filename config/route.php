<?php

return [
	//全局中间件
	'@middleware' => [
		'before' => [],
		'after' => [],
	],
	'/home' => [
		//控制器中的全局中间件
		'@middleware' => [\W7\App\Middleware\TestMiddleware::class],
		'welcome' => [
			'index' => [
				'method' => 'POST,GET',
				'middleware' => [],
			],
			'index1' => [
				'method' => 'POST,GET',
				'middleware' => [],
			],
			'index2' => [
				'method' => 'POST,GET',
				'middleware' => [],
			],
		],
	],
	'test' => [
		'task' => [
			'method' => 'GET',
		]
	]
];