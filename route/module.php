<?php

//如果group设置了middleware，则该分组下的所有路由将拥有该middleware
irouter()->middleware('TestMiddleware')->name('group-name')->group('/module', function (\W7\Core\Route\Route $route) {
//	如果路由定义了name，则按照该路由定义的name，即route-name
	$route->name('route-name')->post('/query/index', [\W7\App\Controller\Module\BuildController::class, 'index']);
//	如果未定义name，默认name生成方式为分组路由的name按.连接，再拼接上当前路由的action，即group-name.index
	$route->post('/info/index', 'Module\InfoController@index');
	$route->name('closure-handle')->post('/info/handle', function () {
		return 'closure handle';
	});
});