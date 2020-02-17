<?php


namespace W7\Tests\Future;

use W7\Tests\TestCase;

class ArgsClass {
	public $sum;

	public function __construct($test1 = 0, $test2 = 0) {
		$this->sum = $test1 + $test2;
	}
}

class ContainerTest extends TestCase {
	public function testGetByArgs() {
		$instance = iloader()->get(ArgsClass::class, [
			1,
			2
		]);
		$this->assertSame(3, $instance->sum);

		try {
			iloader()->get(ArgsClass::class, [
				1,
				new ArgsClass()
			]);
		} catch (\Throwable $e) {
			$this->assertSame('when an object is included in a parameter, it cannot be singularized by a parameter', $e->getMessage());
		}
	}
}