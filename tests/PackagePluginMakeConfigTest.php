<?php

namespace W7\Tests;

class PackagePluginMakeConfigTest extends TestCase {
	public function setUp(): void {
		$cmd = 'cd ' . BASE_PATH . ' && composer dump';
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