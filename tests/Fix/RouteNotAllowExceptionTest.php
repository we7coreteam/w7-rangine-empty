<?php

namespace W7\Tests\Fix;

use W7\Core\Exception\RouteNotAllowException;
use W7\Tests\TestCase;

class RouteNotAllowExceptionTest extends TestCase {
	public function testMessage() {
		try {
			throw new RouteNotAllowException('TEST');
		} catch (\Throwable $e) {
			$message = json_decode($e->getMessage(), true);
			$this->assertSame('TEST', $message['error']);
		}
	}
}