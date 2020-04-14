<?php

namespace W7\Tests\Server\Tcp;

use W7\Http\Message\Server\Request;
use W7\Tests\TestCase;

class RequestTest extends TestCase {
	public function testUrl() {
		/**
		 * @var Request $request
		 */
		$data = json_encode([
			'uri' => '/home/api-get'
		]);
		$request = (new Request('POST', '/'))->loadFromTcpData($data);

		$this->assertSame('/home/api-get', $request->getUri()->getPath());
	}

	public function testMethod() {
		/**
		 * @var Request $request
		 */
		$data = json_encode([
			'uri' => '/home/api-get'
		]);
		$request = (new Request('POST', '/'))->loadFromTcpData($data);

		$this->assertSame('POST', $request->getMethod());
	}

	public function testPost() {
		/**
		 * @var Request $request
		 */
		$data = json_encode([
			'uri' => '/home/api-get',
			'data' => [
				'test' => 1,
				'test1' => 2
			]
		]);
		$request = (new Request('POST', '/'))->loadFromTcpData($data);

		$this->assertSame(1, $request->post('test'));
		$this->assertSame(2, $request->post('test1'));


		/**
		 * @var Request $request
		 */
		$data = json_encode([
			'uri' => '/home/api-get'
		]);
		$request = (new Request('POST', '/'))->loadFromTcpData($data);

		$this->assertSame(false, $request->post('test', false));
		$this->assertSame(false, $request->post('test1', false));
	}
}