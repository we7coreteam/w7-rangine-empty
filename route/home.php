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

irouter()->middleware(\W7\App\Middleware\TestMiddleware::class)->group('', function (\W7\Core\Route\Route $route) {
	$route->get('/home/api-get[/{id:\d+}]', [\W7\App\Controller\Home\WelcomeController::class, 'apiGet']);
});


irouter()->get('/http-client', 'Home\ClientController@http');
irouter()->get('/tcp-client', 'Home\ClientController@tcp');
irouter()->post('/server', 'Home\ClientController@server');