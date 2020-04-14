<?php

namespace W7\Tests\Server\Http\Fpm;

use W7\Http\Message\Server\Request;
use W7\Http\Message\Upload\UploadedFile;
use W7\Tests\TestCase;

class RequestTest extends TestCase {
	public function testUrl() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame('/home/api-get', $request->getUri()->getPath());


		$_SERVER['SCRIPT_FILENAME'] = '/';
		$_SERVER['REQUEST_URI'] = '/home/api-get';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame('/home/api-get', $request->getUri()->getPath());
	}

	public function testMethod() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame('GET', $request->getMethod());

		$_SERVER['REQUEST_METHOD'] = 'POST';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame('POST', $request->getMethod());

		$_SERVER['REQUEST_METHOD'] = 'DELETE';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame('DELETE', $request->getMethod());
	}

	public function testGet() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_GET = [
			'test' => 1,
			'test1' => 2
		];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame(1, $request->getQueryParams()['test']);
		$this->assertSame(2, $request->getQueryParams()['test1']);


		$_GET = [];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame(false, $request->getQueryParams()['test'] ?? false);
		$this->assertSame(false, $request->getQueryParams()['test1'] ?? false);
	}

	public function testPost() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST = [
			'test' => 1,
			'test1' => 2
		];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame(1, $request->post('test'));
		$this->assertSame(2, $request->post('test1'));


		$_POST = [];
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame(false, $request->post('test', false));
		$this->assertSame(false, $request->post('test1', false));
	}

	public function testHeader() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		$_SERVER['HTTP_USER_AGENT'] = 'we7test-develop';
		$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'zh-CN,zh;q=0.9,en;q=0.8';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

		$this->assertSame('we7test-develop', $request->header('user-agent')[0]);
		$this->assertSame('zh-CN,zh;q=0.9,en;q=0.8', $request->header('accept-language')[0]);
	}

	public function testCookie() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';
		$_COOKIE['PHPSESSID'] = '7poabb6g46fcltg2p4ko9rbhj3';

		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();
		$this->assertSame('7poabb6g46fcltg2p4ko9rbhj3', $request->cookie('PHPSESSID'));
		$this->assertSame('', $request->cookie('test', ''));
	}

	public function testUpload() {
		$_SERVER['SCRIPT_FILENAME'] = '/index.php';
		$_SERVER['REQUEST_URI'] = '/index.php/home/api-get';

		$_FILES = [
			'test_file' => new UploadedFile(__FILE__, 'test.php'),
			'test1_file' => new UploadedFile(__FILE__, 'test1.php')
		];

		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();

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
		$request = Request::loadFromFpmRequest();
		icontext()->setRequest($request);
		$this->assertSame('172.16.1.24', getClientIp());

		unset($_SERVER['REMOTE_ADDR']);
		$_SERVER['HTTP_X-Forwarded-For'] = '172.16.1.25';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();
		icontext()->setRequest($request);
		$this->assertSame('172.16.1.25', getClientIp());

		unset($_SERVER['HTTP_X-Forwarded-For']);
		$_SERVER['HTTP_X-Real-IP'] = '172.16.1.26';
		/**
		 * @var Request $request
		 */
		$request = Request::loadFromFpmRequest();
		icontext()->setRequest($request);
		$this->assertSame('172.16.1.26', getClientIp());
	}
}