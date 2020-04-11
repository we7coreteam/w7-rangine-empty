<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\ArgvInput;
use W7\App\Event\TestAutoEvent;
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
		$eventFile = APP_PATH . '/Event/TestEventEvent.php';

		$this->assertSame(true, file_exists($listenerFile));
		$this->assertSame(true, file_exists($eventFile));

		unlink($listenerFile);
		unlink($eventFile);
	}

	public function testAutoRegister() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(BASE_PATH . '/tests/Util/Event', APP_PATH . '/Event');
		$filesystem->copyDirectory(BASE_PATH . '/tests/Util/Listener', APP_PATH . '/Listener');

		$cmd = 'cd ' . BASE_PATH . '/' . ' && composer dump-autoload';
		exec($cmd);
		include_once BASE_PATH . '/vendor/composer/rangine/autoload/config/event.php';
		$eventDispatcher = new EventDispatcher();
		$eventDispatcher->register();

		$this->assertSame(true, $eventDispatcher->hasListeners(TestAutoEvent::class));
		$this->assertSame(true, $eventDispatcher->hasListeners(\W7\App\Event\Test\TestAutoEvent::class));

		$filesystem->delete(APP_PATH . '/Event/Test/TestAutoEvent.php');
		$filesystem->delete(APP_PATH . '/Listener/Test/TestAutoListener.php');
		$filesystem->deleteDirectory(APP_PATH . '/Event/Test');
		$filesystem->deleteDirectory(APP_PATH . '/Listener/Test');
		$filesystem->delete(APP_PATH . '/Event/TestAutoEvent.php');
		$filesystem->delete(APP_PATH . '/Listener/TestAutoListener.php');
	}
}