<?php

namespace W7\Tests;

use Swoole\Process;
use W7\Core\Process\ProcessAbstract;
use W7\Core\Process\ProcessFactory;

class TestProcess extends ProcessAbstract {
	public function check() {
		// TODO: Implement check() method.
	}

	protected function run(Process $process) {
		// TODO: Implement run() method.
	}
}

class Test1Process extends ProcessAbstract {
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
		$processFactory->add('test', TestProcess::class, 2);
		$processFactory->add('test1', Test1Process::class, 1);

		$processFactory->make(0);
		$processFactory->make(1);
		$processFactory->make(2);

		$this->assertSame(true, $processFactory->get(0) instanceof TestProcess);
		$this->assertSame('test', $processFactory->get(0)->getName());
		$this->assertSame(true, $processFactory->get(1) instanceof TestProcess);
		$this->assertSame(true, $processFactory->get(2) instanceof Test1Process);
		$this->assertSame(true, $processFactory->getByName('test', 0) instanceof TestProcess);
		$this->assertSame(true, $processFactory->getByName('test1', 0) instanceof Test1Process);
		$this->assertSame(3, $processFactory->count());
	}
}