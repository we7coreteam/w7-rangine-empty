<?php
/**
 * @author donknap
 * @date 19-4-9 下午2:27
 */

namespace W7\Tests;


use W7\Core\Config\Env\Env;

class ConfigTest extends TestCase {
	public function testDefaultEnv() {
		(new Env(BASE_PATH))->load();
		$this->assertEquals(getenv('CACHE_DEFAULT_HOST'), '127.0.0.1');
	}

	public function testDevelopEnv() {
		copy(__DIR__ . '/Util/Env/.env.develop', BASE_PATH . '/.env.develop');

		putenv('ENV_NAME=develop');
		(new Env(BASE_PATH))->load();

		$this->assertEquals(getenv('TEST_DEVELOP'), 1);

		unlink(BASE_PATH . '/.env.develop');
	}

	public function testLoadConfig() {
		$log = iconfig()->getUserConfig('log');
		$this->assertEquals('stack', $log['default']);
	}

	public function testSet() {
		$config = iconfig()->getUserConfig('app');
		$config['test'] = 1;
		iconfig()->setUserConfig('app', $config);

		$this->assertSame(1, iconfig()->getUserConfig('app')['test']);
	}

	public function testServerConfig() {
		$server = iconfig()->getServer();

		$this->assertSame(10000, $server['common']['max_request']);

		$config = iconfig()->getUserConfig('server');
		$config['common']['max_request'] = 5000;
		iconfig()->setUserConfig('server', $config);

		$server = iconfig()->getServer();

		$this->assertSame(5000, $server['common']['max_request']);
	}
}