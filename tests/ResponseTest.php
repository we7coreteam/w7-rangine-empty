<?php

namespace W7\Tests;

use W7\Contract\Support\Arrayable;
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

	public function testSugar() {
		$responseOrigin = new Response();
		$response = $responseOrigin->redirect('http://baidu.com', 301);
		$this->assertEquals($response->getStatusCode(), 301);
		$this->assertEquals($response->getHeader('location')[0], 'http://baidu.com');

		$response = $responseOrigin->json('test-json');
		$this->assertEquals($response->getBody()->getContents(), '{"data":"test-json"}');
		$this->assertEquals($response->getHeader('Content-Type')[0], 'application/json');
		$this->assertEquals($response->getCharset(), 'utf-8');

		$response = $responseOrigin->json(123);
		$this->assertEquals($response->getBody()->getContents(), '{"data":123}');

		$response = $responseOrigin->json(0);
		$this->assertEquals($response->getBody()->getContents(), '{"data":0}');

		$response = $responseOrigin->json(['test-json', 'a' => 'test-json-a']);
		$this->assertEquals($response->getBody()->getContents(), '{"0":"test-json","a":"test-json-a"}');

		$testObject = new \ArrayObject(['test-json', 'a' => 'test-json-a']);
		$response = $responseOrigin->json($testObject);
		$this->assertEquals($response->getBody()->getContents(), '{"0":"test-json","a":"test-json-a"}');

		$testObject = new testJsonArrayAble();
		$response = $responseOrigin->json($testObject);
		$this->assertEquals($response->getBody()->getContents(), '{"0":"test-json","a":"test-json-a"}');

		$response = $responseOrigin->raw('test-raw');
		$this->assertEquals($response->getBody()->getContents(), 'test-raw');
		$this->assertEquals($response->getHeader('Content-Type')[0], 'text/plain');
		$this->assertEquals($response->getCharset(), 'utf-8');

		$response = $responseOrigin->raw(0, 500);
		$this->assertEquals($response->getBody()->getContents(), 0);
		$this->assertEquals($response->getStatusCode(), 500);

		$response = $responseOrigin->raw(false);
		$this->assertEquals($response->getBody()->getContents(), '');

		$response = $responseOrigin->html('<h1>test</h1>');
		$this->assertEquals($response->getBody()->getContents(), '<h1>test</h1>');
		$this->assertEquals($response->getHeader('Content-Type')[0], 'text/html');
	}
}

class testJsonArrayAble implements Arrayable {

	/**
	 * @inheritDoc
	 */
	public function toArray(): array {
		return ['test-json', 'a' => 'test-json-a'];
	}
}