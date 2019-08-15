<?php

namespace W7\Tests;

use W7\Core\Session\Session;
use W7\Http\Message\Server\Request;

class SessionTest extends TestCase {
	private function initConfig() {
		$config = iconfig()->getUserConfig('app');
		$config['session'] = [
			'name' => session_name(),
		];

		iconfig()->setUserConfig('app', $config);
	}

	public function testSession() {
		$this->initConfig();

		$request = new \Swoole\Http\Request();
		$request->cookie[session_name()] = '5mbfp9jigdkcbpco0senh0omj8';
		$session = new Session(Request::loadFromSwooleRequest($request));
		$session->set('test', 1);

		$this->assertSame(1, $session->get('test'));
		$this->assertSame('5mbfp9jigdkcbpco0senh0omj8', $session->getId());

		$session = new Session(Request::loadFromSwooleRequest(new \Swoole\Http\Request()));
		$session->set('test', 1);

		$this->assertSame(1, $session->get('test'));
		$this->assertNotEmpty($session->getId());

		$session->destroy();
		$this->assertSame('', $session->get('test'));
	}
}