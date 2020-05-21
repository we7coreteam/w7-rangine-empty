<?php
/**
 * This file is part of Rangine
 *
 * (c) We7Team 2019 <https://www.rangine.com/>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com/ for more details
 */
irouter()->middleware('TestMiddleware')->get('/', 'Home\WelcomeController@index');

irouter()->middleware(\W7\App\Middleware\TestMiddleware::class)->group('', function (\W7\Core\Route\Router $route) {
	$route->any('/home/api-get[/{id:\d+}]', [\W7\App\Controller\Home\WelcomeController::class, 'apiGet']);
});

//此路由是302跳转
irouter()->redirect('/index.js', '/static/testjs.js');
irouter()->redirect('/index', '/index.html');

//此路由直接显示内容,
irouter()->get('/change-name.js', '/static/testjs.js');
irouter()->get('/show-pic', '/image/testpic.jpg');

irouter()->get('/http-client', 'Home\ClientController@http');
irouter()->get('/tcp-client', 'Home\ClientController@tcp');
irouter()->post('/server', 'Home\ClientController@server');