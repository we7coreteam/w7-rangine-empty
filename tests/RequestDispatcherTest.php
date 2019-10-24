<?php

namespace W7\Tests;

use FastRoute\Dispatcher\GroupCountBased;
use Illuminate\Filesystem\Filesystem;
use W7\App;
use W7\App\Middleware\Dispatcher1Middleware;
use W7\App\Middleware\DispatcherMiddleware;
use W7\Core\Exception\FaviconException;
use W7\Core\Helper\Storage\Context;
use W7\Core\Middleware\ControllerMiddleware;
use W7\Core\Middleware\MiddlewareHandler;
use W7\Core\Route\RouteMapping;
use W7\Http\Message\Server\Request;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Dispatcher;
use W7\Http\Server\Server;

class RequestDispatcherTest extends TestCase {
	public function testDispatcher() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(__DIR__ . '/Middlewares', APP_PATH . '/Middleware');

		App::$server = new Server();
		$this->addRoute();

		$request = new Request('GET', '/test');
		$response = new Response();
		icontext()->setResponse($response);
		$dispatcher = new Dispatcher();

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);
		$route = $method->invoke($dispatcher, $request);
		$request = $request->withAttribute('route', $route);

		$middleWares = $dispatcher->getMiddlewareMapping()->getRouteMiddleWares($route);
		$this->assertSame(DispatcherMiddleware::class, $middleWares[0][0]);
		$this->assertSame(Dispatcher1Middleware::class, $middleWares[1][0]);
		$this->assertSame(ControllerMiddleware::class, $middleWares[2][0]);
		$this->assertSame(\W7\Core\Middleware\LastMiddleware::class, $middleWares[3][0]);

		$middlewareHandler = new MiddlewareHandler($middleWares);
		$response = $middlewareHandler->handle($request);

		$this->assertSame('{"data":1}', $response->getBody()->getContents());

		$filesystem->delete([
			APP_PATH . '/Middleware/DispatcherMiddleware.php',
			APP_PATH . '/Middleware/Dispatcher1Middleware.php'
		]);
	}

	public function testIgnoreRoute() {
		App::$server = new Server();
		$request = new Request('GET', '/favicon.ico');
		$dispatcher = new Dispatcher();

		$reflect = new \ReflectionClass($dispatcher);
		$method = $reflect->getMethod('getRoute');
		$method->setAccessible(true);
		try {
			$method->invoke($dispatcher, $request);
		} catch (\Throwable $e) {
			$this->assertSame(true, $e instanceof FaviconException);
			$this->assertSame('Route Ignore', $e->getMessage());
			$this->assertSame(404, $e->getCode());
		}

	}

	private function addRoute() {
		irouter()->middleware([
			DispatcherMiddleware::class,
			Dispatcher1Middleware::class
		])->get('/test', function () {
			return 1;
		});

		$routeInfo = iloader()->get(RouteMapping::class)->getMapping();
		$route = new GroupCountBased($routeInfo);
		iloader()->set(Context::ROUTE_KEY, $route);
	}
}