<?php
/**
 * @author donknap
 * @date 19-4-18 下午2:58
 */

namespace W7\Tests;


use FastRoute\Dispatcher\GroupCountBased;
use W7\App\Middleware\GatewayCheckSiteMiddleware;
use W7\Core\Route\Route;
use W7\Core\Route\RouteMapping;

class RouteConfigTest extends TestCase {
	public function testFuncAdd() {
		$routeMapping = new RouteMapping();
		$routeMapping->setRouteConfig([$this->getConfig()]);
		$routeMapping->getMapping();

		irouter()->post('/user', function () {return '/user';});
		irouter()->name('user1')->middleware('AppCheckMiddleware')->get('/user/{name}', function () {return '/user/{name}';});
		irouter()->post('/user/get', function () {return '/user';});

		irouter()->middleware('AppCheckMiddleware')->name('test2')->group('/module1', function (Route $route) {
			$route->post('/info', function () {return '/module1/info';});
			$route->name('test-colsure')->post('/build', function () {return '/module1/build';});
		});

		irouter()->name('test3')->group('/module3', function (Route $route) {
			$route->post('/info', 'Module\BuildController@info');
			$route->name('test-build')->post('/build', 'Module\BuildController@build');
		});

		irouter()->name('group-name')->middleware(['AppCheckMiddleware', 'GatewayCheckSiteMiddleware'])->group('/module2', function (Route $route) {
			$route->get('/info', function () {return '/module2/info';});
			$route->get('/info1', 'Module\InfoController@build');
			$route->name('test-info1')->get('/info2', 'Module\InfoController@build');
			$route->options('/info', function () {return '/module2/build';});
			$route->name('test4')->group('/module3', function (Route $route) {
				$route->post('/info', 'Module\InfoController@info');
				$route->name('test-build')->post('/build', 'Module\InfoController@build');
				$route->name('test-handle')->post('/handle', function () {return 'Module\InfoController@build';});
				$route->post('/handle1', function () {return 'Module\InfoController@build';});

				$route->middleware('CheckAccessTokenMiddleware')->name('test5')->group('/module4', function (Route $route) {
					$route->post('/info', 'Module\InfoController@info');
					$route->name('test-build')->post('/build', 'Module\InfoController@build');
					$route->name('test-handle')->post('/handle', function () {return 'Module\InfoController@build';});
					$route->post('/handle1', function () {return 'Module\InfoController@build';});
				});
				$route->group('/module5', function (Route $route) {
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
		irouter()->middleware('GatewayCheckSiteMiddleware')->group('/app', function (\W7\Core\Route\Route $route) {
			$route->name('resource-test')->group('/module', function (\W7\Core\Route\Route $route) {
				$route->get('/info/index', 'Module\InfoController@index');
				$route->middleware('CheckUrlIsBlackListMiddleware')->group('/info', function (\W7\Core\Route\Route $route) {
					$route->get('/test1/index', 'Module\QueryController@index');
					$route->apiResource('test', 'Module\SettingController');
				});
			});
			$route->group('/module1', function (\W7\Core\Route\Route $route) {
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
}