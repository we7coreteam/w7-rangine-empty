<?php

namespace W7\Tests\Future;

use W7\Core\Session\Session;
use W7\Http\Message\Server\Request;
use W7\Tests\TestCase;

class SessionTest extends TestCase {
	public function testHas() {
		$session = new Session();
		$session->start(new Request('GET', '/'));
		$session->set('test', 1);

		$this->assertSame(true, $session->has('test'));
		$this->assertSame(false, $session->has('test1'));
	}

	public function testAll() {
		$session = new Session();
		$session->start(new Request('GET', '/'));
		$session->set('test', 1);
		$session->set('test1', 2);

		$data = $session->all();

		$this->assertSame(1, $data['test']);
		$this->assertSame(2, $data['test1']);
	}
}