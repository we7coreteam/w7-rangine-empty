<?php

namespace W7\Tests;

use W7\Core\Pool\CoPoolAbstract;

class Connection {

}

class Pool extends CoPoolAbstract {
	public function createConnection() {
		return new Connection();
	}
}

class PoolTest extends TestCase {
	public function testMakePool() {
		$pool = new Pool('test');
		$pool->setMaxCount(10);

		$this->assertSame(0, $pool->getIdleCount());
		$this->assertSame(10, $pool->getMaxCount());
	}

	public function testMakeConnection() {
		$pool = new Pool('test');
		$pool->setMaxCount(10);

		$connection = $pool->getConnection();
		$this->assertSame(true, $connection instanceof Connection);
		$this->assertSame(0, $pool->getIdleCount());

		$reflect = new \ReflectionClass($pool);
		$wait = $reflect->getProperty('waitCount');
		$wait->setAccessible(true);
		$this->assertSame(0, $wait->getValue($pool));

		$busy = $reflect->getProperty('busyCount');
		$busy->setAccessible(true);
		$this->assertSame(1, $busy->getValue($pool));

		go(function () use ($pool, $connection, $busy) {
			$pool->releaseConnection($connection);
			$this->assertSame(0, $busy->getValue($pool));
			$this->assertSame(1, $pool->getIdleCount());
		});
	}

	public function testSuspendAndResume() {
		go(function () {
			$pool = new Pool('test');
			$pool->setMaxCount(1);

			$connection = $pool->getConnection();
			$this->assertSame(true, $connection instanceof Connection);
			$this->assertSame(0, $pool->getIdleCount());

			$reflect = new \ReflectionClass($pool);
			$wait = $reflect->getProperty('waitCount');
			$wait->setAccessible(true);
			$this->assertSame(0, $wait->getValue($pool));

			$busy = $reflect->getProperty('busyCount');
			$busy->setAccessible(true);
			$this->assertSame(1, $busy->getValue($pool));

			go(function () use ($pool, $connection, $wait) {
				\Swoole\Coroutine\System::sleep(1);
				$this->assertSame(1, $wait->getValue($pool));
				$pool->releaseConnection($connection);
				$this->assertSame(0, $pool->getIdleCount());
			});
			$connection1 = $pool->getConnection();
			$this->assertSame(true, $connection === $connection1);
			$this->assertSame(0, $wait->getValue($pool));
			$this->assertSame(1, $busy->getValue($pool));
		});
	}
}