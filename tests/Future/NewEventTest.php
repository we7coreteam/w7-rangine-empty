<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\ArgvInput;
use W7\App\Event\Test\TestEvent as TestTestEvent;
use W7\App\Event\TestEvent;
use W7\Console\Application;
use W7\Core\Dispatcher\EventDispatcher;

class NewEventTest extends TestCase {

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
		$eventFile = APP_PATH . '/Event/TestEvent.php';

		$this->assertSame(true, file_exists($listenerFile));
		$this->assertSame(true, file_exists($eventFile));

		unlink($listenerFile);
		unlink($eventFile);
	}

	public function testAutoRegister() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(__DIR__ . '/../Util/Event', APP_PATH . '/Event');
		$filesystem->copyDirectory(__DIR__ . '/../Util/Listener', APP_PATH . '/Listener');

		$eventDispatcher = new EventDispatcher();
		$eventDispatcher->autoRegisterEvents(__DIR__ . '/../Util/Event', 'W7\\App');

		$this->assertSame(true, $eventDispatcher->hasListeners(TestEvent::class));
		$this->assertSame(true, $eventDispatcher->hasListeners(TestTestEvent::class));

		$filesystem->deleteDirectory(APP_PATH . '/Event/Test');
		$filesystem->deleteDirectory(APP_PATH . '/Listener/Test');
		$filesystem->delete(APP_PATH . '/Event/TestEvent.php');
		$filesystem->delete(APP_PATH . '/Listener/TestListener.php');
	}
}