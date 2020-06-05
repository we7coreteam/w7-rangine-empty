<?php

namespace W7\Tests\Future;

use W7\Tests\TestCase;

class ConfigTest extends TestCase {
	public function testGet() {
		$env = iconfig()->get('app.setting.env');
		$this->assertNotEmpty($env);

		$test = iconfig()->get('app.test.test');
		$this->assertEmpty($test);
	}

	public function testSet() {
		$test = iconfig()->get('app.test1.test');
		$this->assertEmpty($test);

		iconfig()->set('app.test1.test', 1);
		$test = iconfig()->get('app.test1.test');
		$this->assertSame(1, $test);
	}

	public function testServiceConfig() {
		$httpHost = iconfig()->get('server.http.host');
		$this->assertSame('0.0.0.0', $httpHost);

		$this->assertSame(10000, iconfig()->get('server.common.max_request'));
	}
}