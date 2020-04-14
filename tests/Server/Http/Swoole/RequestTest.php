<?php

namespace W7\Tests\Server\Http\Swoole;

use PHPUnit\Framework\TestCase;
use W7\Http\Message\Server\Request;
use W7\Http\Message\Upload\UploadedFile;
use W7\Tests\Server\SwooleRequest;

class RequestTest extends TestCase {
	public function testUrl() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame('/home/api-get', $request->getUri()->getPath());

		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame('/home/api-get', $request->getUri()->getPath());
	}

	public function testMethod() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'GET'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame('GET', $request->getMethod());

		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'POST'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame('POST', $request->getMethod());

		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame('DELETE', $request->getMethod());
	}

	public function testGet() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$swooleRequest->get = [
			'test' => 1,
			'test1' => 2
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame(1, $request->getQueryParams()['test']);
		$this->assertSame(2, $request->getQueryParams()['test1']);


		$swooleRequest->get = [];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame(false, $request->getQueryParams()['test'] ?? false);
		$this->assertSame(false, $request->getQueryParams()['test1'] ?? false);
	}

	public function testPost() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$swooleRequest->post = [
			'test' => 1,
			'test1' => 2
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame(1, $request->post('test'));
		$this->assertSame(2, $request->post('test1'));


		$swooleRequest->post = [];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame(false, $request->post('test', false));
		$this->assertSame(false, $request->post('test1', false));
	}

	public function testHeader() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$swooleRequest->header = [
			'user-agent' => 'we7test-develop',
			'accept-language' => 'zh-CN,zh;q=0.9,en;q=0.8'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame('we7test-develop', $request->header('user-agent')[0]);
		$this->assertSame('zh-CN,zh;q=0.9,en;q=0.8', $request->header('accept-language')[0]);
	}

	public function testCookie() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$swooleRequest->cookie = [
			'PHPSESSID' => '7poabb6g46fcltg2p4ko9rbhj3'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);
		$this->assertSame('7poabb6g46fcltg2p4ko9rbhj3', $request->cookie('PHPSESSID'));
		$this->assertSame('', $request->cookie('test', ''));
	}

	public function testUpload() {
		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$swooleRequest->files = [
			'test_file' => new UploadedFile(__FILE__, 'test.php'),
			'test1_file' => new UploadedFile(__FILE__, 'test1.php')
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);

		$this->assertSame(__DIR__, $request->file('test_file')->getPath());
		$this->assertSame('RequestTest.php', $request->file('test_file')->getBasename());

		$this->assertSame(__DIR__, $request->file('test1_file')->getPath());
		$this->assertSame('RequestTest.php', $request->file('test1_file')->getBasename());
	}

	public function testGetClientIp() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		$_SERVER['REMOTE_ADDR'] = '172.16.1.24';

		/**
		 * @var Request $request
		 */
		$swooleRequest = new SwooleRequest();
		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE',
			'remote_addr' => '172.16.1.24'
		];
		$request = Request::loadFromSwooleRequest($swooleRequest);
		icontext()->setRequest($request);
		$this->assertSame('172.16.1.24', getClientIp());

		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE',
		];
		$swooleRequest->header = [
			'X-Forwarded-For' => '172.16.1.25'
		];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromSwooleRequest($swooleRequest);
		icontext()->setRequest($request);
		$this->assertSame('172.16.1.25', getClientIp());

		$swooleRequest->server = [
			'request_uri' => '/home/api-get?test=1',
			'request_method' => 'DELETE'
		];
		$swooleRequest->header = [
			'X-Real-IP' => '172.16.1.26'
		];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromSwooleRequest($swooleRequest);
		icontext()->setRequest($request);
		$this->assertSame('172.16.1.26', getClientIp());
	}
}