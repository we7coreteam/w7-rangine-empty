<?php

namespace W7\Tests;

use W7\Core\Server\ServerEnum;
use W7\Http\Server\Server;

class ServerTest extends TestCase {
	public function testDefaultConfig() {
		$httpServer = new Server();

		$this->assertSame(2, $httpServer->setting['worker_num']);
		$this->assertSame(SWOOLE_PROCESS, $httpServer->setting['mode']);
		$this->assertSame(SWOOLE_TCP, $httpServer->setting['sock_type']);
	}

	public function testOverDefaultConfig() {
		$config = iconfig()->getServer();
		$config[ServerEnum::TYPE_HTTP]['mode'] = SWOOLE_BASE;
		iconfig()->setUserConfig('server', $config);

		$httpServer = new Server();

		$this->assertSame(2, $httpServer->setting['worker_num']);
		$this->assertSame(SWOOLE_BASE, $httpServer->setting['mode']);
		$this->assertSame(SWOOLE_TCP, $httpServer->setting['sock_type']);
	}

	public function testErrorConfig() {
		$config = iconfig()->getServer();
		$config[ServerEnum::TYPE_HTTP]['host'] = null;
		iconfig()->setUserConfig('server', $config);

		try{
			new Server();
		} catch (\Throwable $e) {
			$this->assertSame('server host error', $e->getMessage());
		}
	}
}