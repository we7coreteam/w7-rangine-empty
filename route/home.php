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
use W7\Core\Facades\Router;

Router::middleware('TestMiddleware')->get('/', 'Home\WelcomeController@index');

Router::middleware(\W7\App\Middleware\TestMiddleware::class)->group('', function (\W7\Core\Route\Router $route) {
	$route->any('/home/api-get[/{id:\d+}]', [\W7\App\Controller\Home\WelcomeController::class, 'apiGet']);
});

//此路由是302跳转
Router::redirect('/index.js', '/static/testjs.js');
Router::redirect('/index', '/index.html');

//此路由直接显示内容,
Router::get('/change-name.js', '/static/testjs.js');
Router::get('/show-pic', '/image/testpic.jpg');

Router::get('/http-client', 'Home\ClientController@http');
Router::get('/tcp-client', 'Home\ClientController@tcp');
Router::post('/server', 'Home\ClientController@server');