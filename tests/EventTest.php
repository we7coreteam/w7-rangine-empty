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