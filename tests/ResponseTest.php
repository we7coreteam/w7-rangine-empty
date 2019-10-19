<?php

namespace W7\Tests;

use W7\Http\Message\Formatter\ResponseFormatterInterface;
use W7\Http\Message\Server\Response;

class UserFormatter implements ResponseFormatterInterface {
	public function formatter(Response $response): Response {
		$response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'text/plain');
		$response->getCharset() && $response = $response->withCharset($response->getCharset());

		$data = $response->getData();
		if ($data) {
			$response = $response->withContent(serialize($data));
		}

		return $response;
	}
}

class ResponseTest extends TestCase {
	public function testRaw() {
		$response = new Response();
		$response = $response->raw('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('test', $content);
		$this->assertSame('text/plain', $response->getHeader('Content-Type')[0]);
	}

	public function testJson() {
		$response = new Response();
		$response = $response->json(['test']);

		$content = $response->getBody()->getContents();
		$this->assertSame('["test"]', $content);
		$this->assertSame('application/json', $response->getHeader('Content-Type')[0]);
	}

	public function testHtml() {
		$response = new Response();
		$response = $response->html('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('test', $content);
		$this->assertSame('text/html', $response->getHeader('Content-Type')[0]);
	}

	public function testDefaultFormatter() {
		$response = new Response();
		$response = $response->withData('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('{"data":"test"}', $content);
		$this->assertSame('application/json', $response->getHeader('Content-Type')[0]);
	}

	public function testUserFormatter() {
		$response = new Response();
		$response->setFormatter(new UserFormatter());
		$response = $response->withData('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('s:4:"test";', $content);
		$this->assertSame('text/plain', $response->getHeader('Content-Type')[0]);
	}

	public function testWithContent() {
		$response = new Response();
		$response = $response->withContent('test');

		$content = $response->getBody()->getContents();
		$this->assertSame('test', $content);
	}
}