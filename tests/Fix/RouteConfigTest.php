<?php

namespace W7\Tests\Fix;

use FastRoute\Dispatcher\GroupCountBased;
use W7\Core\Middleware\MiddlewareAbstract;
use W7\Core\Route\RouteMapping;
use W7\Tests\TestCase;

class TestMiddleware extends MiddlewareAbstract {

}

class Test1Middleware extends MiddlewareAbstract {

}

class RouteConfigTest extends TestCase {
	public function testFix() {
		$routeMapping = new RouteMapping();
		$routeMapping->setRouteConfig([$this->getConfig()]);
		$routeMapping->getMapping();
		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);

		$this->assertSame('test-name', $dispatch->dispatch('GET', '/jsdata/app/hot')[1]['name']);
		$this->assertSame('conf-test.large', $dispatch->dispatch('GET', '/jsdata/app/test-large')[1]['name']);
		$this->assertSame('top', $dispatch->dispatch('GET', '/jsdata/popularize/top')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('GET', '/p-js/test/a')[1]['name']);
		$this->assertSame('W7\App\Middleware\W7\Tests\Fix\Test1Middleware', $dispatch->dispatch('GET', '/p-js/test/a')[1]['middleware']['before'][0][0]);
		$this->assertSame('b', $dispatch->dispatch('GET', '/p-js/test/b')[1]['name']);
		$this->assertSame('c', $dispatch->dispatch('GET', '/p-js/test/c')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('GET', '/p-js/test1/a')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('POST', '/p-js/test1/a')[1]['name']);
		$this->assertSame('b', $dispatch->dispatch('GET', '/p-js/test1/b')[1]['name']);
		$this->assertSame('W7\App\Middleware\W7\Tests\Fix\TestMiddleware', $dispatch->dispatch('GET', '/p-js/test1/b')[1]['middleware']['before'][1][0]);
		$this->assertSame('c1', $dispatch->dispatch('GET', '/p-js/test1/p-c/c1')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('GET', '/p-js/test-2/a')[1]['name']);
		$this->assertSame(1, $dispatch->dispatch('POST', '/p-js/test-2/a')[1]['name']);
		$this->assertSame('W7\App\Middleware\W7\Tests\Fix\TestMiddleware', $dispatch->dispatch('POST', '/p-js/test-2/a')[1]['middleware']['before'][1][0]);
		$this->assertSame('test-2.b', $dispatch->dispatch('GET', '/p-js/test-2/b')[1]['name']);
		$this->assertSame('test-2.c1', $dispatch->dispatch('GET', '/p-js/test-2/p-c/c1')[1]['name']);
	}

	private function getConfig() {
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
}