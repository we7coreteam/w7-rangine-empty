<?php
/**
 * @author donknap
 * @date 19-4-19 下午7:22
 */

irouter()->group('/module1', function (\W7\Core\Route\Router $route) {
	$route->post('/setting/save1', 'Vendor\Test\Module\SettingController@save1');
});

//return [
//	'jsdata1' => [
//		'method' => 'GET',
//		'app' => [
//			//热门应用
//			'hot' => [
//				'uri' => '/jsdata1/app/hot1[/{limit:\d+}]',
//				'handler' => '\Vendor\Test\Jsdata\AppController@hot1',
//			]
//		]
//	],
//];