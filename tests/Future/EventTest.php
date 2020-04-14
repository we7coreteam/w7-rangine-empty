<?php

namespace W7\Tests\Future;

use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Tests\TestCase;

class EventTest extends TestCase {

	public function testMakeListenerAndEvent() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:listener');

		$command->run(new ArgvInput([
			'input',
			'--name=testEvent'
		]), ioutputer());

		$listenerFile = APP_PATH . '/Listener/TestEventListener.php';
		$eventFile = APP_PATH . '/Event/TestEventEvent.php';

		$this->assertSame(true, file_exists($listenerFile));
		$this->assertSame(true, file_exists($eventFile));

		unlink($listenerFile);
		unlink($eventFile);
	}
}