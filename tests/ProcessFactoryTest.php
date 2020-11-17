<?php

namespace W7\Tests;

use Swoole\Process;
use W7\Core\Process\ProcessAbstract;
use W7\Core\Process\ProcessFactory;

class TestFactoryProcess extends ProcessAbstract {
	public function check() {
		// TODO: Implement check() method.
	}

	protected function run(Process $process) {
		// TODO: Implement run() method.
	}
}

class TestFactory1Process extends ProcessAbstract {
	public function check() {
		// TODO: Implement check() method.
	}

	protected function run(Process $process) {
		// TODO: Implement run() method.
	}
}

class ProcessFactoryTest extends TestCase {
	public function testRegister() {
		$processFactory = new ProcessFactory();
		$processFactory->add('test', TestFactoryProcess::class, 2);
		$processFactory->add('test1', TestFactory1Process::class, 1);

		$processFactory->makeById(0);
		$processFactory->makeById(1);
		$processFactory->makeById(2);

		$this->assertSame(true, $processFactory->getById(0) instanceof TestFactoryProcess);
		$this->assertSame('test', $processFactory->getById(0)->getName());
		$this->assertSame(true, $processFactory->getById(1) instanceof TestFactoryProcess);
		$this->assertSame(true, $processFactory->getById(2) instanceof TestFactory1Process);
		$this->assertSame(true, $processFactory->getByName('test', 0) instanceof TestFactoryProcess);
		$this->assertSame(true, $processFactory->getByName('test1', 0) instanceof TestFactory1Process);
		$this->assertSame(3, $processFactory->count());
	}
}