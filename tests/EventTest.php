<?php

namespace W7\Tests;

use W7\Core\Dispatcher\EventDispatcher;

class EventTest extends TestCase {
	public function testSet() {
		$event = new EventDispatcher();
		$event->listen('test', function () {
			return 'test';
		});

		$this->assertSame(true, $event->hasListeners('test'));
	}

	public function testMultiEvent() {
		$event = new EventDispatcher();
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

	public function testRunAll() {
		$event = new EventDispatcher();
		$event->listen('test', function () {
			return 'test';
		});

		$this->assertSame('test', $event->dispatch('test')[0]);
	}

	public function testRunOne() {
		$event = new EventDispatcher();
		$event->listen('test', function () {
			return 'test';
		});

		$this->assertSame('test', $event->dispatch('test', [], true));
	}
}