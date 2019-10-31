<?php

namespace W7\Tests;

use FastRoute\Dispatcher\GroupCountBased;
use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Route\RouteMapping;

class ControllerTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:controller');
		$command->run(new ArgvInput([
			'input',
			'--name=test/index'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Controller/Test/IndexController.php'));
		$this->assertSame(true, file_exists(BASE_PATH . '/route/test.php'));

		$routeMapping = iloader()->singleton(RouteMapping::class);
		$routeMapping->getMapping();
		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);
		$route = $dispatch->dispatch('GET', '/test/index');
		$this->assertSame('\W7\App\Controller\Test\IndexController', $route[1]['handler'][0]);

		unlink(BASE_PATH . '/route/test.php');
		unlink(APP_PATH . '/Controller/Test/IndexController.php');
		rmdir(APP_PATH . '/Controller/Test');
	}
}