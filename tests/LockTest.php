<?php

namespace W7\Tests;

use Throwable;
use W7\Lock\Exception\LockTimeoutException;
use W7\Lock\Facade\LockFactory;

class LockTest extends TestCase {
	public function testGet() {
		$name = 'test';
		$seconds = 12;

		$result = LockFactory::getLock($name, $seconds)->get(function () use ($name, $seconds) {
			$result = LockFactory::getLock($name, $seconds)->get(function () {

			});

			$this->assertSame(false, $result);
			return true;
		});
		$this->assertSame(true, $result);
	}

	public function testBlock() {
		$name = 'test';
		$seconds = 2;

		$result = LockFactory::getLock($name, $seconds)->block(3, function () use ($name, $seconds) {
			sleep(2);
			return true;
		});
		$this->assertSame(true, $result);

		$lock = LockFactory::getLock($name, 4);
		$lock->acquire();
		try {
			$result = LockFactory::getLock($name, $seconds)->block(3, function () use ($name, $seconds) {
				sleep(4);
				return true;
			});
		} catch (Throwable $e) {
			$this->assertSame(true, $e instanceof LockTimeoutException);
		} finally {
			$lock->release();
		}
	}
}