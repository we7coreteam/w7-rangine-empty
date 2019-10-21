<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use Psr\Http\Message\ResponseInterface;
use W7\App;
use W7\Core\Exception\HandlerExceptions;
use W7\Core\Exception\ResponseExceptionAbstract;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Server;

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
		!defined('ENV') && define('ENV', RELEASE);
		icontext()->setResponse(new Response());

		try{
			throw new UserException('test');
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertSame('test', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}

	public function testReleaseRender() {
		!defined('ENV') && define('ENV', RELEASE);
		parent::setUp();
		App::$server = new Server();
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
		!defined('ENV') && define('ENV', RELEASE);
		putenv('SETTING_ERROR_REPORTING=' . E_ALL);
		parent::setUp();
		App::$server = new Server();
		icontext()->setResponse(new Response());

		try{
			1/0;
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertSame('{"error":"系统内部错误"}', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}

	public function testUserHandler() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(__DIR__ . '/Handler/Exception', APP_PATH . '/Handler/Exception');

		!defined('ENV') && define('ENV', RELEASE);
		putenv('SETTING_ERROR_REPORTING=' . E_ALL);
		parent::setUp();
		App::$server = new Server();
		icontext()->setResponse(new Response());

		try{
			1/0;
		} catch (\Throwable $e) {
			$response = iloader()->get(HandlerExceptions::class)->handle($e);
			$this->assertSame('test', $response->getBody()->getContents());
			$this->assertSame(false, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}

		$filesystem->deleteDirectory(APP_PATH . '/Handler/Exception');
	}

	public function testDebugRender() {
		define('ENV', DEBUG);
		parent::setUp();
		App::$server = new Server();
		icontext()->setResponse(new Response());

		try{
			throw new \RuntimeException('test');
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$this->assertContains('test', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}
}