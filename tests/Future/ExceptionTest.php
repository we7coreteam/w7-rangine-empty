<?php

namespace W7\Tests\Future;

use Psr\Http\Message\ResponseInterface;
use W7\App;
use W7\Core\Exception\HandlerExceptions;
use W7\Core\Exception\ResponseExceptionAbstract;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Server;
use W7\Tests\TestCase;

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

	public function testUserHandler() {
		if (!class_exists('W7\App\Handler\Exception\ExceptionHandler', false)) {
			require __DIR__ . '/../Util/Handler/Exception/ExceptionHandler.php';
		}

		!defined('ENV') && define('ENV', RELEASE);
		putenv('SETTING_ERROR_REPORTING=' . E_ALL);
		parent::setUp();
		iloader()->get(HandlerExceptions::class)->setHandler(new \W7\App\Handler\Exception\ExceptionHandler());
		App::$server = new Server();
		icontext()->setResponse(new Response());

		try{
			1/0;
		} catch (\Throwable $e) {
			$response = iloader()->get(HandlerExceptions::class)->handle($e);
			$this->assertSame('test', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}
}