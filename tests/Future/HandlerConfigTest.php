<?php

namespace W7\Tests\Future;

use W7\Tests\TestCase;

class HandlerConfigTest extends TestCase {
	public function setUp(): void {
		$cmd = 'cd ' . dirname(__DIR__, 2) . ' && composer dump';
		exec($cmd);
		parent::setUp();
	}

	public function testConfig() {
		$handlerConfig = iconfig()->getUserConfig('handler');
		$this->assertArrayHasKey('session', $handlerConfig);
		$this->assertArrayHasKey('view', $handlerConfig);
		$this->assertArrayHasKey('cache', $handlerConfig);
		$this->assertArrayHasKey('log', $handlerConfig);

		$this->assertArrayHasKey('file', $handlerConfig['session']);
		$this->assertSame('W7\Core\Session\Handler\FileHandler', $handlerConfig['session']['file']);
	}
}