<?php

namespace W7\Tests\Server\WebSocket;

use Swoole\WebSocket\Frame;
use W7\Http\Message\Server\Request;
use W7\Tests\TestCase;

class RequestTest extends TestCase {
	public function testUrl() {
		/**
		 * @var Request $request
		 */
		$frame = new Frame();
		$frame->fd = 1;
		$frame->data = json_encode([
			'uri' => '/home/api-get'
		]);
		$request = (new Request('POST', '/'))->loadFromWSFrame($frame);

		$this->assertSame('/home/api-get', $request->getUri()->getPath());
	}

	public function testMethod() {
		/**
		 * @var Request $request
		 */
		$frame = new Frame();
		$frame->fd = 1;
		$frame->data = json_encode([
			'uri' => '/home/api-get'
		]);
		$request = (new Request('POST', '/'))->loadFromWSFrame($frame);

		$this->assertSame('POST', $request->getMethod());
	}

	public function testPost() {
		/**
		 * @var Request $request
		 */
		$frame = new Frame();
		$frame->fd = 1;
		$frame->data = json_encode([
			'uri' => '/home/api-get',
			'data' => [
				'test' => 1,
				'test1' => 2
			]
		]);
		$request = (new Request('POST', '/'))->loadFromWSFrame($frame);

		$this->assertSame(1, $request->post('test'));
		$this->assertSame(2, $request->post('test1'));


		/**
		 * @var Request $request
		 */
		$frame = new Frame();
		$frame->fd = 1;
		$frame->data = json_encode([
			'uri' => '/home/api-get'
		]);
		$request = (new Request('POST', '/'))->loadFromWSFrame($frame);

		$this->assertSame(false, $request->post('test', false));
		$this->assertSame(false, $request->post('test1', false));
	}
}