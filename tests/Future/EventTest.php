<?php

namespace W7\Tests;

use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Dispatcher\EventDispatcher;

class EventTest extends TestCase {

	public function testMakeListenerAndEvent() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:listener');

		$command->run(new ArgvInput([
			'input',
			'--name=test1'
		]), ioutputer());

		$listenerFile = APP_PATH . '/Listener/Test1Listener.php';
		$eventFile = APP_PATH . '/Event/Test1Event.php';

		$this->assertSame(true, file_exists($listenerFile));
		$this->assertSame(true, file_exists($eventFile));

		unlink($listenerFile);
		unlink($eventFile);
    }
}