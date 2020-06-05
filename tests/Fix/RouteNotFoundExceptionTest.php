<?php

namespace W7\Tests\Fix;

use W7\Core\Exception\RouteNotFoundException;
use W7\Tests\TestCase;

class RouteNotFoundExceptionTest extends TestCase {
	public function testMessage() {
		try {
			throw new RouteNotFoundException('TEST');
		} catch (\Throwable $e) {
			$message = json_decode($e->getMessage(), true);
			$this->assertSame('TEST', $message['error']);
		}
	}
}