<?php
/**
 * @author donknap
 * @date 19-4-23 下午4:00
 */

return [
	'jsdata' => [
		'method' => 'GET',
		'prefix' => '/jsdata',
		//路由分組name
		'name' => 'route-name',
		//公共的中间件
		'middleware' => [
			\W7\App\Middleware\TestMiddleware::class
		],
		'app' => [
			'name' => 'route-name1',
			//每一个节点的中间件配置都会被子节点继承
			'middleware' => [
				'TestMiddleware',
			],
			'hot' => [
				//当该路由的name未定义时，默认name生成方式为分组路由的name按.连接，再拼接上当前路由的action
				//name: route-name.route-name1.hot
				'uri' => '/jsdata/app/hot[/{limit:\d+}]', //指定路由
				'handler' => 'Home\WelcomeController@hot', //指定处理方法
			]
		],
		'popularize' => [
			'top' => [
				//如果该路由定义了name，则按照该路由定义的name
				'name' => 'route-name1',
			],   //默认生成路由为 /jsdata/popularize/top
			'bottom' => [
				'name' => 'closure-handle',
				'handler' => function () { return 'closure handle'; }
			], //默认handler为 Jsdata\PopularizeController@bottom
			'ads' => [
				//name: route-name.ads
			],
		],
		'file-encrypt' => [
			//默认路由 /jsdata/file-encrypt
			'index' => [
				'handler' => 'Home\WelcomeController@index',
			],
		],
	],
];