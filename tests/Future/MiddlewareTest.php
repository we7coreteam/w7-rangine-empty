<?php

namespace W7\Tests\Future;

use W7\Http\Server\Dispatcher;
use W7\Http\Session\Middleware\SessionMiddleware;
use W7\Tests\TestCase;

class MiddlewareTest extends TestCase {
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