<?php

namespace W7\Tests;


use W7\Core\Log\Handler\BufferHandler;
use W7\Core\Log\Logger;

class LoggerTest extends TestCase {
	private function clearLog() {
		//清空当前日志
		$files = glob(RUNTIME_PATH . '/logs/*.log');
		if ($files) {
			foreach ($files as $file) {
				unlink($file);
			}
		}
	}

	public function testWrite() {
		$this->clearLog();

		ilogger()->debug('test debug');

		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$content = file_get_contents($files[0]);

		$this->assertSame(true, strpos($content, 'DEBUG: test debug') !== false);

		$this->clearLog();
	}

	public function testDebugInInfo() {
		$this->clearLog();
		$handler = ilogger()->getHandlers()[0];
		$handler->setLevel(Logger::INFO);
		ilogger()->debug('test debug');
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(false, count($files) > 0);
	}

	public function testErrorInInfo() {
		$this->clearLog();
		$handler = ilogger()->getHandlers()[0];
		$handler->setLevel(Logger::INFO);
		ilogger()->error('test debug');
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(true, count($files) > 0);

		$this->clearLog();
	}

	public function testBufferOne() {
		$this->clearLog();
		/**
		 * @var BufferHandler $handler
		 */
		ilogger()->bufferLimit = 1;
		$handler = ilogger()->getHandlers()[0];
		$handlerReflect = new \ReflectionClass($handler);
		$property = $handlerReflect->getProperty('bufferLimit');
		$property->setAccessible(true);
		$property->setValue($handler, 1);

		ilogger()->debug('test');

		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(true, count($files) > 0);

		$this->clearLog();
	}

	public function testBuffer() {
		$this->clearLog();
		/**
		 * @var BufferHandler $handler
		 */
		ilogger()->bufferLimit = 5;
		$handler = ilogger()->getHandlers()[0];
		$handlerReflect = new \ReflectionClass($handler);
		$property = $handlerReflect->getProperty('bufferLimit');
		$property->setAccessible(true);
		$property->setValue($handler, 5);

		ilogger()->debug('test');
		ilogger()->debug('test');
		ilogger()->debug('test');
		ilogger()->debug('test');
		ilogger()->debug('test');
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(false, count($files) > 0);

		ilogger()->debug('test');
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(true, count($files) > 0);

		$this->clearLog();
	}
}