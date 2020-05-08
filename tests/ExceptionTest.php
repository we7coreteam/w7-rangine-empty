<?php

namespace W7\Tests;

use Symfony\Component\Console\Input\ArgvInput;
use Psr\Http\Message\ResponseInterface;
use W7\App;
use W7\Console\Application;
use W7\Core\Exception\HandlerExceptions;
use W7\Core\Exception\ResponseExceptionAbstract;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Server;
use W7\App\Exception\Test\IndexException;

class UserException extends ResponseExceptionAbstract {
	public function render(): ResponseInterface {
		return $this->response->withContent($this->getMessage());
	}
}

class ExceptionTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:exception');
		$command->run(new ArgvInput([
			'input',
			'--name=test/index'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Exception/Test/IndexException.php'));

		unlink(APP_PATH . '/Exception/Test/IndexException.php');
		rmdir(APP_PATH . '/Exception/Test');


		//测试生成 ResponseExcepiton

		$command = $application->get('make:exception');
		$command->run(new ArgvInput([
			'input',
			'--name=httpErrorTest',
			'--type=response'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Exception/HttpErrorTestException.php'));
		$this->assertStringContainsString('extends ResponseExceptionAbstract', file_get_contents(APP_PATH . '/Exception/HttpErrorTestException.php'));

		unlink(APP_PATH . '/Exception/HttpErrorTestException.php');
	}

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
		$server = new \W7\Fpm\Server\Server();
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

	public function testDebugRender() {
		$server = new \W7\Fpm\Server\Server();
		!defined('ENV') && define('ENV', DEBUG);
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

	public function testReleaseRender() {
		$server = new \W7\Fpm\Server\Server();
		!defined('ENV') && define('ENV', RELEASE);
		parent::setUp();
		App::$server = new Server();
		icontext()->setResponse(new Response());

		try{
			throw new \RuntimeException('test');
		} catch (\Throwable $e) {
			$response = (new HandlerExceptions())->handle($e);
			$content = json_decode($response->getBody()->getContents(), true);
			$this->assertSame('系统内部错误', $content['error']);
		}
	}

	public function testUserHandler() {
		require __DIR__ . '/Util/Handler/Exception/ExceptionHandler.php';

		!defined('ENV') && define('ENV', RELEASE);
		putenv('SETTING_ERROR_REPORTING=' . E_ALL);
		parent::setUp();
		iloader()->get(HandlerExceptions::class)->setHandler(\W7\App\Handler\Exception\ExceptionHandler::class);
		App::$server = new Server();
		icontext()->setResponse(new Response());

		try{
			1/0;
		} catch (\Throwable $e) {
			$response = iloader()->get(HandlerExceptions::class)->handle($e);
			$this->assertSame('user exception handler', $response->getBody()->getContents());
			$this->assertSame(true, !empty(glob(RUNTIME_PATH . '/logs/w7-*.log')));
		}
	}
}