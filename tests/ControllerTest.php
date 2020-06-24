<?php

namespace W7\Tests;

use FastRoute\Dispatcher\GroupCountBased;
use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Facades\Router;
use W7\Core\Helper\FileLoader;
use W7\Core\Route\RouteMapping;

class ControllerTest extends TestCase {
	public function testMakeWithOutDir() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:controller');
		$command->run(new ArgvInput([
			'input',
			'--name=user'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Controller/UserController.php'));
		$this->assertSame(true, file_exists(BASE_PATH . '/route/common.php'));

		$routeMapping = new RouteMapping(Router::getFacadeRoot(), new FileLoader());
		$routeMapping->getMapping();
		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);
		$route = $dispatch->dispatch('GET', '/user');
		$this->assertSame('W7\App\Controller\UserController', $route[1]['handler'][0]);

		$command->run(new ArgvInput([
			'input',
			'--name=person'
		]), ioutputer());
		$this->assertSame(true, file_exists(APP_PATH . '/Controller/PersonController.php'));
		$route = $dispatch->dispatch('GET', '/person');
		$this->assertSame(true, empty($route[1]));


		unlink(BASE_PATH . '/route/common.php');
		unlink(APP_PATH . '/Controller/PersonController.php');
		unlink(APP_PATH . '/Controller/UserController.php');
	}
	public function testMakeWithDir() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:controller');
		$command->run(new ArgvInput([
			'input',
			'--name=test\index'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Controller/Test/IndexController.php'));
		$this->assertSame(true, file_exists(BASE_PATH . '/route/test.php'));

		require_once BASE_PATH . '/route/test.php';
		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);
		$route = $dispatch->dispatch('GET', '/test/index');
		$this->assertSame('W7\App\Controller\Test\IndexController', $route[1]['handler'][0]);

		unlink(BASE_PATH . '/route/test.php');
		unlink(APP_PATH . '/Controller/Test/IndexController.php');
		rmdir(APP_PATH . '/Controller/Test');
	}

	public function testMakeExistDir() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:controller');
		$command->run(new ArgvInput([
			'input',
			'--name=home/test'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Controller/Home/TestController.php'));

		$routeMapping = new RouteMapping(Router::getFacadeRoot(), new FileLoader());
		$routeMapping->getMapping();
		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);
		$route = $dispatch->dispatch('GET', '/home/test');
		$this->assertSame(true, empty($route[1]));

		unlink(APP_PATH . '/Controller/Home/TestController.php');
	}
}