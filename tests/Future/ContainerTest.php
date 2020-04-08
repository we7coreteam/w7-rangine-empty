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

	public function testAppend() {
		icontainer()->set('test_arr', [1,2,3,4,5]);
		$value = icontainer()->get('test_arr');
		$this->assertSame(5, count($value));

		icontainer()->append('test_arr',[6,7,8,9]);
		$value = icontainer()->get('test_arr');
		$this->assertSame(5, count($value));


		icontainer()->set('test_arr1', [
			'test' => 1,
			'test1' => 2
		]);
		$value = icontainer()->get('test_arr1');
		$this->assertSame(2, count($value));

		icontainer()->append('test_arr1',[
			'test' => 4,
			'test3' => 2
		]);
		$value = icontainer()->get('test_arr1');
		$this->assertSame(3, count($value));
		$this->assertSame(4, $value['test']);
	}
}