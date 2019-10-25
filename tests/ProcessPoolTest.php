<?php

namespace W7\Tests;
use Swoole\Process;
use W7\Core\Process\Pool\DependentPool;
use W7\Core\Process\Pool\IndependentPool;
use W7\Core\Process\ProcessAbstract;
use W7\Core\Server\ServerEnum;

$checkReturn = true;

class TestProcess extends ProcessAbstract {
	public function check() : bool {
		global $checkReturn;
		return $checkReturn;
	}

	protected function run(Process $process) {
		// TODO: Implement run() method.
	}
}

class Test1Process extends ProcessAbstract {
	public function check() : bool {
		global $checkReturn;
		return $checkReturn;
	}

	protected function run(Process $process) {
		// TODO: Implement run() method.
	}
}

class ProcessPoolTest extends TestCase {
	public function testIndependentRegister() {
		$pool = new IndependentPool(ServerEnum::TYPE_HTTP, [
			'pid_file' => '/test'
		]);
		global $checkReturn;
		$checkReturn = true;

		$pool->registerProcess('test', TestProcess::class, 1);
		$pool->registerProcess('test1', Test1Process::class, 1);

		$pool->getProcessFactory()->make(0);
		$pool->getProcessFactory()->make(1);

		$this->assertSame(true, $pool->getProcessFactory()->getByName('test', 0) instanceof TestProcess);
		$this->assertSame(true, $pool->getProcessFactory()->getByName('test1', 0) instanceof Test1Process);
	}

	public function testIndependentRegisterCheck() {
		$pool = new IndependentPool(ServerEnum::TYPE_HTTP, [
			'pid_file' => '/test'
		]);
		global $checkReturn;
		$checkReturn = false;

		$pool->registerProcess('test', TestProcess::class, 1);
		$pool->registerProcess('test1', Test1Process::class, 1);

		try{
			$pool->getProcessFactory()->make(0);
		} catch (\Throwable $e) {
			$this->assertSame('Undefined offset: 0', $e->getMessage());
		}
	}

	public function testDependentRegister() {
		$pool = new DependentPool(ServerEnum::TYPE_HTTP, [
			'pid_file' => '/test'
		]);
		global $checkReturn;
		$checkReturn = true;

		$pool->registerProcess('test', TestProcess::class, 1);
		$pool->registerProcess('test1', Test1Process::class, 1);

		$pool->getProcessFactory()->make(0);
		$pool->getProcessFactory()->make(1);

		$this->assertSame(true, $pool->getProcessFactory()->getByName('test', 0) instanceof TestProcess);
		$this->assertSame(true, $pool->getProcessFactory()->getByName('test1', 0) instanceof Test1Process);
	}

	public function testDependentRegisterCheck() {
		$pool = new DependentPool(ServerEnum::TYPE_HTTP, [
			'pid_file' => '/test'
		]);
		global $checkReturn;
		$checkReturn = false;

		$pool->registerProcess('test', TestProcess::class, 1);
		$pool->registerProcess('test1', Test1Process::class, 1);

		try{
			$pool->getProcessFactory()->make(0);
		} catch (\Throwable $e) {
			$this->assertSame('Undefined offset: 0', $e->getMessage());
		}
	}
}