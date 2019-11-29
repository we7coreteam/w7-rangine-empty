<?php

namespace W7\Tests;

class ErrorTest extends TestCase {
	public function testEAll() {
		copy(__DIR__ . '/Util/Env/.env.e_all', BASE_PATH . '/.env.e_all');
		putenv('ENV_NAME=e_all');
		parent::setUp();

		$error = '';
		try{
			1/0;
		} catch (\Throwable $e) {
			$error = $e->getMessage();
		}
		$this->assertSame('Division by zero', $error);

		unlink(BASE_PATH . '/.env.e_all');
	}

	public function testNotice() {
		copy(__DIR__ . '/Util/Env/.env.notice', BASE_PATH . '/.env.notice');
		putenv('ENV_NAME=notice');
		parent::setUp();

		$error = '';
		try{
			$test['test'];
		} catch (\Throwable $e) {
			$error = $e->getMessage();
		}
		$this->assertSame('', $error);

		unlink(BASE_PATH . '/.env.notice');
	}
}