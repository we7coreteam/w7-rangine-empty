<?php

namespace W7\Tests\Future;

use Symfony\Component\Console\Input\ArgvInput;
use W7\App;
use W7\Console\Application;
use W7\Core\Facades\Router;
use W7\Core\Helper\FileLoader;
use W7\Core\Route\RouteDispatcher;
use W7\Core\Route\RouteMapping;
use W7\Tests\TestCase;

class RouteCacheController {
	public function index() {

	}
}

class RouteCacheTest extends TestCase {
	public function testCache() {
		/**
		 * @var Application $application
		 */
		$application = icontainer()->singleton(Application::class);
		$application->get('route:clear')->run(new ArgvInput([
			'test'
		]), ioutputer());
		$this->assertSame(false, file_exists(App::getApp()->getRouteCachePath()));

		irouter()->get('/route-cache',  [RouteCacheController::class, 'index']);
		$routeDispatcher = RouteDispatcher::getDispatcherWithRouteMapping(RouteMapping::class);
		$result = $routeDispatcher->dispatch('GET', '/route-cache');
		$this->assertSame(true, !empty($result[1]));


		$application->get('route:cache')->run(new ArgvInput([
			'test'
		]), ioutputer());
		$routeDispatcher = RouteDispatcher::getDispatcherWithRouteMapping(RouteMapping::class);
		irouter()->get('/route-cache1', [RouteCacheController::class, 'index']);

		$result = $routeDispatcher->dispatch('GET', '/route-cache1');
		$this->assertSame(true, empty($result[1]));
		$result = $routeDispatcher->dispatch('GET', '/route-cache');
		$this->assertSame(true, !empty($result[1]));


		$application->get('route:clear')->run(new ArgvInput([
			'test'
		]), ioutputer());

		$routeDispatcher = RouteDispatcher::getDispatcherWithRouteMapping(RouteMapping::class);
		$result = $routeDispatcher->dispatch('GET', '/route-cache1');
		$this->assertSame(true, !empty($result[1]));
	}
}