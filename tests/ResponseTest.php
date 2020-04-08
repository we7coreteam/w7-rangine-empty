<?php

namespace W7\Tests;

use W7\Http\Message\Base\Cookie;
use W7\Http\Message\Server\Response;

class ResponseTest extends TestCase {
	public function testRaw() {
		$response = new Response();
		$response = $response->withHeader('Content-Type', 'text/plain')->withContent('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('test', $content);
		$this->assertSame('text/plain', $response->getHeader('Content-Type')[0]);
	}

	public function testJson() {
		$response = new Response();
		$response = $response->withHeader('Content-Type', 'application/json')->withContent(json_encode(['test']));

		$content = $response->getBody()->getContents();
		$this->assertSame('["test"]', $content);
		$this->assertSame('application/json', $response->getHeader('Content-Type')[0]);
	}

	public function testHtml() {
		$response = new Response();
		$response = $response->withHeader('Content-Type', 'text/html')->withContent('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('test', $content);
		$this->assertSame('text/html', $response->getHeader('Content-Type')[0]);
	}

	public function testWithContent() {
		$response = new Response();
		$response = $response->withContent('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('test', $content);
	}

	public function testCookie() {
		$response = new Response();
		$response = $response->withCookie('test', 1);
		$response = $response->withCookie('test1', new Cookie(
			'test1',
			'2'
		));

		/**
		 * @var Cookie $cookie
		 */
		$cookie = $response->getCookies()['test'];
		$this->assertSame("1", $cookie->getValue());
		$this->assertSame("test", $cookie->getName());

		$cookie = $response->getCookies()['test1'];
		$this->assertSame("2", $cookie->getValue());
		$this->assertSame("test1", $cookie->getName());
	}
}