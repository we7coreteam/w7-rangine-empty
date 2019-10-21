<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use W7\Core\Session\Session;
use W7\Http\Message\Server\Request;

class SessionTest extends TestCase {
	public function testSet() {
		$session = new Session();
		$session->start(new Request('GET', '/'));
		$session->set('test', 1);

		$this->assertSame(1, $session->get('test'));
	}

	public function testDestroy() {
		$session = new Session();
		$session->start(new Request('GET', '/'));
		$session->set('test', 1);
		$session->destroy();

		$this->assertSame('', $session->get('test'));
	}

	public function testGc() {
		$config = iconfig()->getUserConfig('app');
		$config['session'] = [
			'gc_divisor' => 1,
			'gc_probability' => 1,
			'expires' => 1
		];
		iconfig()->setUserConfig('app', $config);

		$session = new Session();
		$session->start(new Request('GET', '/'));
		$session->set('test', 1);

		sleep(2);
		$session->gc();
		$session->gc();

		$sessionReflect = new \ReflectionClass($session);
		$property = $sessionReflect->getProperty('cache');
		$property->setAccessible(true);
		$property->setValue($session, null);

		$this->assertSame('', $sessionReflect->getMethod('get')->invokeArgs($session, ['test']));
	}

	public function testUserHandler() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(__DIR__ . '/Handler/Session', APP_PATH . '/Handler/Session');

		$config = iconfig()->getUserConfig('app');
		$config['session']['handler'] = 'test';
		iconfig()->setUserConfig('app', $config);

		$session = new Session();
		$session->start(new Request('GET', '/'));

		$session->set('test', 1);

		$this->assertSame(1, $session->get('test'));

		$filesystem->deleteDirectory(APP_PATH . '/Handler/Session');
	}
}