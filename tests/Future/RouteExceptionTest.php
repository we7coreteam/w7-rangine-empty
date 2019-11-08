<?php

namespace W7\Tests\Future;

use FastRoute\Dispatcher\GroupCountBased;
use W7\App;
use W7\Core\Exception\RouteNotAllowException;
use W7\Core\Exception\RouteNotFoundException;
use W7\Core\Helper\Storage\Context;
use W7\Core\Route\RouteMapping;
use W7\Http\Message\Server\Request;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Dispatcher;
use W7\Http\Server\Server;
use W7\Tests\TestCase;

class RouteExceptionTest extends TestCase {
	public function testNotFound() {
		$routeInfo = iloader()->get(RouteMapping::class)->getMapping();
		$route = new GroupCountBased($routeInfo);
		iloader()->set(Context::ROUTE_KEY, $route);

		App::$server = new Server();
		$request = new Request('POST', '/post');
		$response = new Response();
		icontext()->setResponse($response);
		$dispatcher = new Dispatcher();

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);

		try {
			$method->invoke($dispatcher, $request);
		} catch (\Throwable $e) {
			$this->assertSame(true, $e instanceof RouteNotFoundException);
			$this->assertSame('Route not found, /post', $e->getMessage());
		}
	}

	public function testNotAllow() {
		$routeInfo = iloader()->get(RouteMapping::class)->getMapping();
		$route = new GroupCountBased($routeInfo);
		iloader()->set(Context::ROUTE_KEY, $route);

		App::$server = new Server();
		$request = new Request('POST', '/');
		$response = new Response();
		icontext()->setResponse($response);
		$dispatcher = new Dispatcher();

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);

		try {
			$method->invoke($dispatcher, $request);
		} catch (\Throwable $e) {
			$this->assertSame(true, $e instanceof RouteNotAllowException);
			$this->assertSame('Route not allowed, /', $e->getMessage());
		}
	}
}