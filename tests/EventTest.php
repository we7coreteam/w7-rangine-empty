<?php

namespace W7\Tests;

use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Dispatcher\EventDispatcher;
use W7\Core\Events\Dispatcher;

class EventTest extends TestCase {

	public function testMakeException() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:listener');

		$command->run(new ArgvInput([
			'input',
			'--name=test'
		]), ioutputer());

		$file = APP_PATH . '/Listener/TestListener.php';

		$this->assertSame(true, file_exists($file));

		unlink($file);
		unlink(APP_PATH . '/Event/TestEvent.php');
	}

	public function testSet() {
		$event = new Dispatcher();
		$event->listen('test', function () {
			return 'test';
		});

		$this->assertSame(true, $event->hasListeners('test'));
	}

	public function testMultiEvent() {
		$event = new Dispatcher();
		$event->listen('test', function () {
			return 'test';
		});
		$event->listen('test', function () {
			return 'test1';
		});
		$event->listen('test', function () {
			return 'test2';
		});

		$this->assertSame('test', $event->dispatch('test')[0]);
		$this->assertSame('test1', $event->dispatch('test')[1]);
		$this->assertSame('test2', $event->dispatch('test')[2]);
	}

	public function testDispatcherAll() {
		$event = new Dispatcher();
		$event->listen('test', function () {
			return 'test';
		});

		$this->assertSame('test', $event->dispatch('test')[0]);
	}

	public function testDispatcherOne() {
		$event = new Dispatcher();
		$event->listen('test', function () {
			return 'test';
		});

		$this->assertSame('test', $event->dispatch('test', [], true));
	}

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