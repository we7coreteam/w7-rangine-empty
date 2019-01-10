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
		'@method' => \W7\Core\Route\Route::METHOD_ALL,
		'welcome' => [
			'index' => [],
			'index1' => [],
			'index2' => [],
		],
	],
	'test' => [
		'task' => [
			'method' => 'GET',
		]
	]
];