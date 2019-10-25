<?php

namespace W7\Tests\Future;

use W7\Http\Server\Server;
use W7\Tests\TestCase;

class ServerTest extends TestCase {
	public function testDefaultConfig() {
		$httpServer = new Server();

		$this->assertSame(1, $httpServer->setting['worker_num']);
		$this->assertSame(SWOOLE_PROCESS, $httpServer->setting['mode']);
		$this->assertSame(SWOOLE_TCP, $httpServer->setting['sock_type']);
	}
}