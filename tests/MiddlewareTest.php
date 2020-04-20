<?php

namespace W7\Tests;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Middleware\MiddlewareAbstract;
use W7\Core\Middleware\MiddlewareHandler;
use W7\Http\Message\Server\Request;
use W7\Http\Message\Server\Response;
use W7\Http\Server\Dispatcher;
use W7\Http\Session\Middleware\SessionMiddleware;

class BeforeMiddleware extends MiddlewareAbstract {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		echo 'before';
		return parent::process($request, $handler);
	}
}

class AfterMiddleware extends MiddlewareAbstract {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		$result = parent::process($request, $handler);
		echo 'after';
		return $result;
	}
}

class LastMiddleware extends MiddlewareAbstract {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		echo 'last';
		return (new Response())->withContent('success');
	}
}


class MiddlewareTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:middleware');
		$command->run(new ArgvInput([
			'input',
			'--name=test1'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Middleware/Test1Middleware.php'));

		unlink(APP_PATH . '/Middleware/Test1Middleware.php');
	}

	public function testRun() {
		$middleWares = [
			[BeforeMiddleware::class],
			[AfterMiddleware::class],
			[LastMiddleware::class]
		];
		ob_start();
		$middlewareHandler = new MiddlewareHandler($middleWares);
		$response = $middlewareHandler->handle(new Request('GET', '/'));
		$echo = ob_get_clean();

		$this->assertSame('beforelastafter', $echo);
		$this->assertSame('success', $response->getBody()->getContents());
	}

	public function testDelete() {
		/**
		 * @var Dispatcher $dispatcher
		 */
		$dispatcher = iloader()->get(Dispatcher::class);
		$mapping = $dispatcher->getMiddlewareMapping();

		$mapping->addBeforeMiddleware(SessionMiddleware::class);
		$this->assertSame(SessionMiddleware::class, $mapping->beforeMiddleware[0][0]);

		$mapping->deleteAfterMiddleware(SessionMiddleware::class);
		$this->assertSame(SessionMiddleware::class, $mapping->beforeMiddleware[0][0]);

		$mapping->deleteBeforeMiddleware(SessionMiddleware::class);
		$this->assertSame(0, count($mapping->beforeMiddleware));
	}
}