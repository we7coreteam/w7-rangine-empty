<?php

namespace W7\Tests;

use Psr\Http\Message\ResponseInterface;
use W7\App;
use W7\Core\Exception\HandlerExceptions;
use W7\Core\Exception\ResponseExceptionAbstract;
use W7\Http\Message\Server\Response;

class UserException extends ResponseExceptionAbstract {
	public function render(): ResponseInterface {
		return $this->response->withContent($this->getMessage());
	}
}

class ExceptionTest extends TestCase {
	public function setUp(): void {
		//清空当前日志
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		if ($files) {
			foreach ($files as $file) {
				unlink($file);
			}
		}
	}

	public function testRender() {
		icontext()->setResponse(new Response());

		try{
			throw new UserException('test');
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertSame('test', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}

	public function testDebugRender() {
		define('ENV', RELEASE);
		parent::setUp();
		App::$server = new \stdClass();
		App::$server->type = 'http';
		icontext()->setResponse(new Response());

		try{
			throw new \RuntimeException('test');
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertContains('test', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}

	public function testReleaseRender() {
		define('ENV', RELEASE);
		parent::setUp();
		App::$server = new \stdClass();
		App::$server->type = 'http';
		icontext()->setResponse(new Response());

		try{
			throw new \RuntimeException('test');
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertSame('{"error":"系统内部错误"}', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}

	public function testErrorRender() {
		define('ENV', RELEASE);
		putenv('SETTING_ERROR_REPORTING=' . E_ALL);
		parent::setUp();
		App::$server = new \stdClass();
		App::$server->type = 'http';
		icontext()->setResponse(new Response());

		try{
			1/0;
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertSame('{"error":"系统内部错误"}', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}
}