<?php
/**
 * @author donknap
 * @date 19-4-23 下午4:00
 */

return [
	'/' => [
		'method' => 'GET',
		'prefix' => '/home',
		//路由分組name
		'name' => 'route-home',
		//公共的中间件
		'middleware' => [
			\W7\App\Middleware\TestMiddleware::class
		],
		'welcome' => [
			'name' => 'route-welcome',
			//每一个节点的中间件配置都会被子节点继承
			'middleware' => [
				'TestMiddleware',
			],
			'index' => [
				//当该路由的name未定义时，默认name生成方式为分组路由的name按.连接，再拼接上当前路由的action
				'handler' => 'Home\WelcomeController@index', //指定处理方法
			]
		]
	],
];