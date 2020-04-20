<?php


namespace W7\Tests;


use Illuminate\Filesystem\Filesystem;
use W7\App\Event\TestAutoEvent;
use W7\Core\Dispatcher\EventDispatcher;

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

		exec($cmd);
	}
}