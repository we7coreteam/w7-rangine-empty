<?php

namespace W7\Tests;

class TestContainer {
	public function __destruct() {
		echo 'test container destruct';
	}
}


class ContainerTest extends TestCase {
	public function testSet() {
		iloader()->set('test', true);

		$this->assertSame(true, iloader()->get('test'));
	}

	public function testHas() {
		iloader()->set('test', true);

		$this->assertSame(true, iloader()->has('test'));
	}

	public function testGet() {
		iloader()->set('test', true);

		$this->assertSame(true, iloader()->get('test'));
	}
	public function testDelete() {
		iloader()->set('test', true);
		$this->assertSame(true, iloader()->has('test'));

		iloader()->delete('test');
		$this->assertSame(false, iloader()->has('test'));
	}

	public function testClear() {
		ob_start();
		$this->clear();
		$echo = ob_get_clean();
		$this->assertSame('test container destruct', $echo);
	}

	public function clear() {
		iloader()->set('test', true);
		$this->assertSame(true, iloader()->has('test'));

		iloader()->clear();
		$this->assertSame(false, iloader()->has('test'));

		iloader()->set('test', TestContainer::class);
		$class = iloader()->get('test');
		$this->assertSame(true, $class instanceof TestContainer);

		iloader()->clear();
	}
}