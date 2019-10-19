<?php

namespace W7\Tests;

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
}