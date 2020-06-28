<?php
/**
 * @author donknap
 * @date 19-4-18 下午2:58
 */

namespace W7\Tests;


use FastRoute\Dispatcher\GroupCountBased;
use W7\App;
use W7\App\Middleware\GatewayCheckSiteMiddleware;
use W7\Core\Exception\RouteNotAllowException;
use W7\Core\Exception\RouteNotFoundException;
use W7\Core\Helper\FileLoader;
use W7\Core\Middleware\MiddlewareAbstract;
use W7\Core\Route\RouteDispatcher;
use W7\Core\Route\Router;
use W7\Core\Route\RouteMapping;
use W7\Http\Message\Server\Request;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Dispatcher;
use W7\Http\Server\Server;

class TestMiddleware extends MiddlewareAbstract {

}

class Test1Middleware extends MiddlewareAbstract {

}

class RouteConfigTest extends TestCase {
	public function testFuncAdd() {
		$routeMapping = new RouteMapping(\W7\Core\Facades\Router::getFacadeRoot(), new FileLoader());
		$routeMapping->setRouteConfig([$this->getConfig()]);
		$routeMapping->getMapping();

		irouter()->post('/user', function () {return '/user';});
		irouter()->name('user1')->middleware('AppCheckMiddleware')->get('/user/{name}', function () {return '/user/{name}';});
		irouter()->post('/user/get', function () {return '/user';});

		irouter()->middleware('AppCheckMiddleware')->name('test2')->group('/module1', function (Router $route) {
			$route->post('/info', function () {return '/module1/info';});
			$route->name('test-colsure')->post('/build', function () {return '/module1/build';});
		});

		irouter()->name('test3')->group('/module3', function (Router $route) {
			$route->post('/info', 'Module\BuildController@info');
			$route->name('test-build')->post('/build', 'Module\BuildController@build');
		});

		irouter()->name('group-name')->middleware(['AppCheckMiddleware', 'GatewayCheckSiteMiddleware'])->group('/module2', function (Router $route) {
			$route->get('/info', function () {return '/module2/info';});
			$route->get('/info1', 'Module\InfoController@build');
			$route->name('test-info1')->get('/info2', 'Module\InfoController@build');
			$route->options('/info', function () {return '/module2/build';});
			$route->name('test4')->group('/module3', function (Router $route) {
				$route->post('/info', 'Module\InfoController@info');
				$route->name('test-build')->post('/build', 'Module\InfoController@build');
				$route->name('test-handle')->post('/handle', function () {return 'Module\InfoController@build';});
				$route->post('/handle1', function () {return 'Module\InfoController@build';});

				$route->middleware('CheckAccessTokenMiddleware')->name('test5')->group('/module4', function (Router $route) {
					$route->post('/info', 'Module\InfoController@info');
					$route->name('test-build')->post('/build', 'Module\InfoController@build');
					$route->name('test-handle')->post('/handle', function () {return 'Module\InfoController@build';});
					$route->post('/handle1', function () {return 'Module\InfoController@build';});
				});
				$route->group('/module5', function (Router $route) {
					$route->name('test-info')->post('/info/{info}', 'Module\InfoController@info');
					$route->post('/info1/{info}', 'Module\InfoController@info');
					$route->name('test-build')->post('/build', 'Module\InfoController@build');
					$route->name('test-handle')->post('/handle', function () {return 'Module\InfoController@build';});
					$route->post('/handle1', function () {return 'Module\InfoController@build';});
				});
			});
		});

		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);

		$result = $dispatch->dispatch('GET', '/jsdata/app/hot/1');
		$this->assertSame('test-name', $result[1]['name']);
		$result = $dispatch->dispatch('GET', '/jsdata/app/large/1');
		$this->assertSame('conf-test.large', $result[1]['name']);
		$result = $dispatch->dispatch('GET', '/jsdata/app/essential/1');
		$this->assertSame('', $result[1]['name']);
		$result = $dispatch->dispatch('GET', '/jsdata/app/recommend/1');
		$this->assertSame('test-handle', $result[1]['name']);
		$result = $dispatch->dispatch('GET', '/jsdata/popularize/top');
		$this->assertSame('top', $result[1]['name']);

		$result = $dispatch->dispatch('GET', '/user/mizhou');
		$this->assertEquals('/user/{name}', $result[1]['handler']());
		$this->assertEquals('user1', $result[1]['name']);
		$this->assertStringContainsString('AppCheckMiddleware', $result[1]['middleware']['before'][0][0]);

		$result = $dispatch->dispatch('GET', '/user');
		$this->assertNotEquals('/user', $result[1]);
		$result = $dispatch->dispatch('POST', '/user');
		$this->assertSame('', $result[1]['name']);

		$result = $dispatch->dispatch('POST', '/module3/info');
		$this->assertSame('test3.info', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module3/build');
		$this->assertSame('test-build', strval($result[1]['name']));

		$result = $dispatch->dispatch('POST', '/module2/module3/info');
		$this->assertSame('group-name.test4.info', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/build');
		$this->assertSame('test-build', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/handle');
		$this->assertSame('test-handle', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/handle1');
		$this->assertSame('', strval($result[1]['name']));
		$this->assertStringContainsString('AppCheckMiddleware', $result[1]['middleware']['before'][0][0]);
		$this->assertStringContainsString('GatewayCheckSiteMiddleware', $result[1]['middleware']['before'][1][0]);

		$result = $dispatch->dispatch('POST', '/module2/module3/module4/info');
		$this->assertSame('group-name.test4.test5.info', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module4/build');
		$this->assertSame('test-build', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module4/handle');
		$this->assertSame('test-handle', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module4/handle1');
		$this->assertSame('', strval($result[1]['name']));
		$this->assertStringContainsString('AppCheckMiddleware', $result[1]['middleware']['before'][0][0]);
		$this->assertStringContainsString('GatewayCheckSiteMiddleware', $result[1]['middleware']['before'][1][0]);
		$this->assertStringContainsString('CheckAccessTokenMiddleware', $result[1]['middleware']['before'][2][0]);

		$result = $dispatch->dispatch('POST', '/module2/module3/module5/info/1');
		$this->assertSame('test-info', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module5/info1/1');
		$this->assertSame('group-name.test4.info', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module5/build');
		$this->assertSame('test-build', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module5/handle');
		$this->assertSame('test-handle', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module2/module3/module5/handle1');
		$this->assertSame('', strval($result[1]['name']));

		$result = $dispatch->dispatch('GET', '/module2/info');
		$this->assertSame('', strval($result[1]['name']));
		$result = $dispatch->dispatch('GET', '/module2/info1');
		$this->assertSame('group-name.build', strval($result[1]['name']));
		$result = $dispatch->dispatch('GET', '/module2/info2');
		$this->assertSame('test-info1', strval($result[1]['name']));

		$result = $dispatch->dispatch('POST', '/module1/info');
		$this->assertSame('', strval($result[1]['name']));
		$result = $dispatch->dispatch('POST', '/module1/build');
		$this->assertSame('test-colsure', strval($result[1]['name']));

		$result = $dispatch->dispatch('GET', '/jsdata/app/hot');
		$this->assertNotEquals('Jsdata\AppController@hot', $result[1]);

		$result = $dispatch->dispatch('POST', '/module1/info');
		$this->assertEquals('/module1/info', $result[1]['handler']());
		$this->assertStringContainsString('AppCheckMiddleware', $result[1]['middleware']['before'][0][0]);

		$result = $dispatch->dispatch('POST', '/module1/build');
		$this->assertEquals('/module1/build', $result[1]['handler']());
		$this->assertStringContainsString('AppCheckMiddleware', $result[1]['middleware']['before'][0][0]);
	}

	public function testGroup() {
		irouter()->middleware('GatewayCheckSiteMiddleware')->group('/app', function (\W7\Core\Route\Router $route) {
			$route->name('resource-test')->group('/module', function (\W7\Core\Route\Router $route) {
				$route->get('/info/index', 'Module\InfoController@index');
				$route->middleware('CheckUrlIsBlackListMiddleware')->group('/info', function (\W7\Core\Route\Router $route) {
					$route->get('/test1/index', 'Module\QueryController@index');
					$route->apiResource('test', 'Module\SettingController');
				});
			});
			$route->group('/module1', function (\W7\Core\Route\Router $route) {
				$route->get('/info1/index1', 'Module\SettingController@index');
			});
		});

		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);

		$result = $dispatch->dispatch('GET', '/app/module/info/test');
		$this->assertEquals('W7\App\Controller\Module\SettingController', $result[1]['handler'][0]);
		$this->assertEquals('index', $result[1]['handler'][1]);
		$result = $dispatch->dispatch('POST', '/app/module/info/test');
		$this->assertEquals('W7\App\Controller\Module\SettingController', $result[1]['handler'][0]);
		$this->assertEquals('store', $result[1]['handler'][1]);
		$result = $dispatch->dispatch('POST', '/app/module/info/test');
		$this->assertEquals('W7\App\Controller\Module\SettingController', $result[1]['handler'][0]);
		$this->assertSame('store', $result[1]['handler'][1]);
		$this->assertSame('resource-test.store', $result[1]['name']);

		$result = $dispatch->dispatch('GET', '/app/module1/info1/index1');
		$this->assertEquals('W7\App\Controller\Module\SettingController', $result[1]['handler'][0]);
		$this->assertSame('index', $result[1]['handler'][1]);
		$this->assertSame('index', $result[1]['name']);
	}


	private function getConfig() {
		return [
			'jsdata' => [
				'method' => 'GET',
				'middleware' => 'AppCheckMiddleware',
				'app' => [
					//热门应用
					'name' => 'conf-test',
					'hot' => [
						'name' => 'test-name',
						'uri' => '/jsdata/app/hot[/{limit:\d+}]',
						'handler' => 'Jsdata\AppController@hot',
					],
					//大应用
					'large' => [
						'middleware' => [GatewayCheckSiteMiddleware::class],
						'uri' => '/jsdata/app/large[/{limit:\d+}]',
						'handler' => 'Jsdata\AppController@large',
					],
					//总下载排行
					'essential' => [
						'uri' => '/jsdata/app/essential[/{limit:\d+}]',
						'handler' => function () {
							return 'Jsdata\AppController@essential';
						}
					],
					//新应用
					'new-app' => [
						'uri' => '/jsdata/app/new-app[/{limit:\d+}]',
						'handler' => 'Jsdata\AppController@newApp',
					],
					//推荐
					'recommend' => [
						'name' => 'test-handle',
						'uri' => '/jsdata/app/recommend[/{limit:\d+}]',
						'handler' => function () {
							return 'Jsdata\AppController@recommend';
						}
					],
				],
				'popularize' => [
					'top' => [],
					'bottom' => [],
					'ads' => [],
				]
			]
		];
	}

	public function testMulti() {
		irouter()->add('GET', '/multi', function () {
			return 'success';
		});

		try {
			irouter()->add('GET', '/multi', function () {
				return 'success';
			});
		} catch (\Throwable $e) {
			$this->assertSame('route "/multi" for method "GET" exists in system', $e->getMessage());
		}
	}

	public function testNotFound() {
		$routeMapping = new RouteMapping(\W7\Core\Facades\Router::getFacadeRoot(), new FileLoader());
		$routeInfo = $routeMapping->getMapping();
		$router = new RouteDispatcher($routeInfo);
		$dispatcher = new Dispatcher();
		$dispatcher->setRouterDispatcher($router);

		App::$server = new Server();
		$request = new Request('POST', '/post');
		$response = new Response();
		icontext()->setResponse($response);

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);

		try {
			$method->invoke($dispatcher, $request);
		} catch (\Throwable $e) {
			$this->assertSame(true, $e instanceof RouteNotFoundException);
			$this->assertSame('{"error":"Route not found, \/post"}', $e->getMessage());
		}
	}

	public function testNotAllow() {
		$routeMapping = new RouteMapping(\W7\Core\Facades\Router::getFacadeRoot(), new FileLoader());
		$router = new RouteDispatcher($routeMapping->getMapping());
		$dispatcher = new Dispatcher();
		$dispatcher->setRouterDispatcher($router);

		App::$server = new Server();
		$request = new Request('POST', '/favicon.ico');
		$response = new Response();
		icontext()->setResponse($response);

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);

		try {
			$method->invoke($dispatcher, $request);
		} catch (\Throwable $e) {
			$this->assertSame(true, $e instanceof RouteNotAllowException);
			$this->assertSame('{"error":"Route not allowed, \/favicon.ico"}', $e->getMessage());
		}
	}

	public function testFix() {
		$routeMapping = new RouteMapping(new Router(), new FileLoader());
		$routeMapping->setRouteConfig([$this->getFixConfig()]);
		$dispatch = new GroupCountBased($routeMapping->getMapping());

		$this->assertSame('test-name', $dispatch->dispatch('GET', '/jsdata/app/hot')[1]['name']);
		$this->assertSame('conf-test.large', $dispatch->dispatch('GET', '/jsdata/app/test-large')[1]['name']);
		$this->assertSame('top', $dispatch->dispatch('GET', '/jsdata/popularize/top')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('GET', '/p-js/test/a')[1]['name']);
		$this->assertSame('W7\App\Middleware\W7\Tests\Test1Middleware', $dispatch->dispatch('GET', '/p-js/test/a')[1]['middleware']['before'][0][0]);
		$this->assertSame('b', $dispatch->dispatch('GET', '/p-js/test/b')[1]['name']);
		$this->assertSame('c', $dispatch->dispatch('GET', '/p-js/test/c')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('GET', '/p-js/test1/a')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('POST', '/p-js/test1/a')[1]['name']);
		$this->assertSame('b', $dispatch->dispatch('GET', '/p-js/test1/b')[1]['name']);
		$this->assertSame('W7\App\Middleware\W7\Tests\TestMiddleware', $dispatch->dispatch('GET', '/p-js/test1/b')[1]['middleware']['before'][1][0]);
		$this->assertSame('c1', $dispatch->dispatch('GET', '/p-js/test1/p-c/c1')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('GET', '/p-js/test-2/a')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('POST', '/p-js/test-2/a')[1]['name']);
		$this->assertSame('W7\App\Middleware\W7\Tests\TestMiddleware', $dispatch->dispatch('POST', '/p-js/test-2/a')[1]['middleware']['before'][1][0]);
		$this->assertSame('test-2.b', $dispatch->dispatch('GET', '/p-js/test-2/b')[1]['name']);
		$this->assertSame('test-2.c1', $dispatch->dispatch('GET', '/p-js/test-2/p-c/c1')[1]['name']);
	}

	private function getFixConfig() {
		return [
			'method' => 'GET',
			'middleware' => [Test1Middleware::class],
			'jsdata' => [
				'middleware' => 'AppCheckMiddleware',
				'app' => [
					//热门应用
					'name' => 'conf-test',
					'hot' => [
						'name' => 'test-name',
						'uri' => '/jsdata/app/hot[/{limit:\d+}]',
						'handler' => 'Jsdata\AppController@hot',
					],
					//大应用
					'large' => [
						'prefix' => '/test-large',
						'middleware' => [TestMiddleware::class],
						'handler' => 'Jsdata\AppController@large',
					]
				],
				'popularize' => [
					'top' => [],
					'bottom' => [],
					'ads' => [],
				]
			],
			'js' => [
				'prefix' => 'p-js',
				'test' => [
					'a' => [
						'name' => 1
					],
					'b' => [
						'middleware' => [TestMiddleware::class]
					],
					'c' => []
				],
				'test1' => [
					'a' => [
						'method' => 'GET, POST',
						'name' => 1
					],
					'b' => [
						'middleware' => [TestMiddleware::class]
					],
					'c' => [
						'prefix' => 'p-c',
						'c1' => []
					]
				],
				'test2' => [
					'prefix' => 'test-2',
					'name' => 'test-2',
					'middleware' => [TestMiddleware::class],
					'a' => [
						'method' => 'GET, POST',
						'name' => 1
					],
					'b' => [

					],
					'c' => [
						'prefix' => 'p-c',
						'c1' => []
					]
				]
			]
		];
	}

	public function testStaticRoute() {
		try{
			irouter()->get('/static', 'static/index.html');
		} catch (\Throwable $e) {
			$this->assertSame('route handler static/index.html error', $e->getMessage());
		}

		irouter()->get('/static', 'index.html');

		$routeInfo = (new RouteMapping(\W7\Core\Facades\Router::getFacadeRoot(), new FileLoader()))->getMapping();
		$router = new GroupCountBased($routeInfo);
		$route = $router->dispatch('GET', '/static');

		$this->assertSame(true, $route[1]['handler'][0] == '\W7\Core\Controller\StaticResourceController');
		$this->assertSame('/static', $route[1]['uri']);
	}
}