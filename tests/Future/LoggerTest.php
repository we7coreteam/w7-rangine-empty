<?php

namespace W7\Tests\Future;

use W7\Core\Log\LogManager;

class LoggerTest extends \W7\Tests\LoggerTest {
	public function testAddChannelWithDebug() {
		$this->clearLog();
		/**
		 * @var LogManager $logManager
		 */
		$logManager = iloader()->get(LogManager::class);

		$this->assertSame(true, empty($logManager->getConfig()['channel']['debug']));

		$logManager->addChannel('debug', 'stream', [
			'path' => RUNTIME_PATH . '/logs/test.log',
			'level' => 'debug'
		]);

		$this->assertSame(false, empty($logManager->getConfig()['channel']['debug']));
		$this->assertSame('stream', $logManager->getConfig()['channel']['database']['driver']);

		ilogger()->channel('debug')->debug('test');

		$this->assertSame(true, file_exists(RUNTIME_PATH . '/logs/test.log'));

		$this->clearLog();
	}

	public function testAddChannelWithInfo() {
		$this->clearLog();
		/**
		 * @var LogManager $logManager
		 */
		$logManager = iloader()->get(LogManager::class);

		$this->assertSame(true, empty($logManager->getConfig()['channel']['test']));

		$logManager->addChannel('test', 'stream', [
			'path' => RUNTIME_PATH . '/logs/test.log',
			'level' => 'info'
		]);

		$this->assertSame(false, empty($logManager->getConfig()['channel']['test']));
		$this->assertSame('stream', $logManager->getConfig()['channel']['database']['driver']);

		ilogger()->channel('test')->debug('test');

		$this->assertSame(false, file_exists(RUNTIME_PATH . '/logs/test.log'));

		ilogger()->channel('test')->info('test');

		$this->assertSame(true, file_exists(RUNTIME_PATH . '/logs/test.log'));

		$this->clearLog();
	}
}