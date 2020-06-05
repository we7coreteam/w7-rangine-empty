<?php

namespace W7\Tests\Fix;

use W7\Core\Exception\ValidatorException;
use W7\Tests\TestCase;

class ValidateExceptionTest extends TestCase {
	public function testMessage() {
		try {
			throw new ValidatorException('TEST');
		} catch (\Throwable $e) {
			$message = json_decode($e->getMessage(), true);
			$this->assertSame('TEST', $message['error']);
		}
	}
}