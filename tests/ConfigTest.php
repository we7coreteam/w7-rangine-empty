<?php
/**
 * @author donknap
 * @date 19-4-9 下午2:27
 */

namespace W7\Tests;

use W7\Core\Config\Config;

class ConfigTest extends TestCase {
	public function testDevelopEnv() {
		$_ENV = [];
		$fileName = BASE_PATH . '/.env.develop';
		if (!file_exists($fileName)) {
			return true;
		}
		putenv('ENV_NAME=develop');

		$config = new Config();
		$developContent = file_get_contents($fileName);

		$this->assertEquals(getenv('DATABASE_DEFAULT_HOST'), '10.0.0.17');
		return $this->assertStringContainsString('DATABASE_DEFAULT_HOST = ' . getenv('DATABASE_DEFAULT_HOST'), $developContent);
	}

	public function testEnv() {
		$_ENV = [];
		$fileName = BASE_PATH . '/.env';
		if (!file_exists($fileName)) {
			return true;
		}
		putenv('ENV_NAME=');

		$config = new Config();
		$developContent = file_get_contents($fileName);

		$this->assertEquals(getenv('DATABASE_DEFAULT_HOST'), '172.16.1.13');
		return $this->assertStringContainsString('DATABASE_DEFAULT_HOST = ' . getenv('DATABASE_DEFAULT_HOST'), $developContent);
	}

	public function testEncrypt() {
		$_ENV = [];
		$fileName = BASE_PATH . '/.env.release.encrypt';
		if (!file_exists($fileName)) {
			return true;
		}
		putenv('ENV_NAME=release.encrypt');

		$config = new Config();
		$developContent = file_get_contents($fileName);

		$this->assertEquals(getenv('SERVER_COMMON_TASK_WORKER_NUM'), '1');
		return $this->assertStringContainsString('SERVER_COMMON_TASK_WORKER_NUM = ' . getenv('SERVER_COMMON_TASK_WORKER_NUM'), $developContent);
	}

	public function testLoadConfig() {
		$log = iconfig()->getUserConfig('log');
		$this->assertEquals('stack', $log['default']);
	}
}