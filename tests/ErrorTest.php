<?php

namespace W7\Tests;

class ErrorTest extends TestCase {
	public function setUp(): void {

	}

	public function testEAll() {
		putenv('ENV_NAME=.e_all');
		file_put_contents(BASE_PATH . '/.env.e_all', file_get_contents(__DIR__ . '/.env.e_all'));
		parent::setUp();

		$error = '';
		try{
			1/0;
		} catch (\Throwable $e) {
			$error = $e->getMessage();
		}
		$this->assertSame('Division by zero', $error);
	}

	public function testNotice() {
		putenv('ENV_NAME=.notice');
		file_put_contents(BASE_PATH . '/.env.notice', file_get_contents(__DIR__ . '/.env.notice'));
		parent::setUp();

		$error = '';
		try{
			$test['test'];
		} catch (\Throwable $e) {
			$error = $e->getMessage();
		}
		$this->assertSame('', $error);
	}
}