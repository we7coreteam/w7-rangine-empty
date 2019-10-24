<?php

namespace W7\Tests\Future;

use W7\App;
use FastRoute\Dispatcher\GroupCountBased;
use W7\Core\Helper\Storage\Context;
use W7\Core\Route\RouteMapping;
use W7\Http\Message\Server\Request;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Dispatcher;
use W7\Http\Server\Server;
use W7\Tests\TestCase;

class RequestDispatcherTest extends TestCase {
	public function testIgnoreRoute() {
		$routeInfo = iloader()->get(RouteMapping::class)->getMapping();
		$route = new GroupCountBased($routeInfo);
		iloader()->set(Context::ROUTE_KEY, $route);

		App::$server = new Server();
		$request = new Request('GET', '/favicon.ico');
		$response = new Response();
		icontext()->setResponse($response);
		$dispatcher = new Dispatcher();

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);

		$route = $method->invoke($dispatcher, $request);
		$this->assertSame(true, $route['controller'] instanceof \Closure);
		$this->assertSame('system', $route['module']);
		$this->assertSame('', $route['controller']()->getBody()->getContents());
	}

	public function testUserIgnoreRoute() {
		irouter()->get('/favicon.ico', function () {
			return 'user favicon';
		});

		$routeInfo = iloader()->get(RouteMapping::class)->getMapping();
		$route = new GroupCountBased($routeInfo);
		iloader()->set(Context::ROUTE_KEY, $route);

		App::$server = new Server();
		$request = new Request('GET', '/favicon.ico');
		$dispatcher = new Dispatcher();

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);

		$route = $method->invoke($dispatcher, $request);
		$this->assertSame(true, $route['controller'] instanceof \Closure);
		$this->assertSame('system', $route['module']);
		$this->assertSame('user favicon', $route['controller']());
	}
}