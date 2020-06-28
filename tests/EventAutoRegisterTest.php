<?php


namespace W7\Tests;


use Illuminate\Filesystem\Filesystem;
use W7\App\Event\TestAutoEvent;
use W7\Core\Dispatcher\EventDispatcher;
use W7\Core\Events\Dispatcher;
use W7\Core\Facades\Event;

class EventAutoRegisterTest extends TestCase {
	public function setUp(): void {

	}

	public function testAutoRegister() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(BASE_PATH . '/tests/Util/Event', APP_PATH . '/Event');
		$filesystem->copyDirectory(BASE_PATH . '/tests/Util/Listener', APP_PATH . '/Listener');

		$cmd = 'cd ' . BASE_PATH . '/' . ' && composer dump-autoload';
		exec($cmd);

		$this->initApp();

		$this->assertSame(true, Event::hasListeners(TestAutoEvent::class));
		$this->assertSame(true, Event::hasListeners(\W7\App\Event\Test\TestAutoEvent::class));

		$filesystem->delete(APP_PATH . '/Event/Test/TestAutoEvent.php');
		$filesystem->delete(APP_PATH . '/Listener/Test/TestAutoListener.php');
		$filesystem->deleteDirectory(APP_PATH . '/Event/Test');
		$filesystem->deleteDirectory(APP_PATH . '/Listener/Test');
		$filesystem->delete(APP_PATH . '/Event/TestAutoEvent.php');
		$filesystem->delete(APP_PATH . '/Listener/TestAutoListener.php');

		exec($cmd);
	}
}