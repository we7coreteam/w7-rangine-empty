<?php

//如果group设置了middleware，则该分组下的所有路由将拥有该middleware
irouter()->middleware('TestMiddleware')->name('session')->group('/session', function (\W7\Core\Route\Route $route) {
//	如果路由定义了name，则按照该路由定义的name，即route-name
	$route->name('session')->get('', [\W7\App\Controller\SessionController::class, 'get']);
	$route->name('session')->post('', [\W7\App\Controller\SessionController::class, 'set']);
	$route->name('session')->delete('', [\W7\App\Controller\SessionController::class, 'destroy']);
});