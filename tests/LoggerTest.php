<?php

namespace W7\Tests;


use Illuminate\Filesystem\Filesystem;
use W7\App\Handler\Log\TestHandler;
use W7\Core\Log\Handler\StreamHandler;
use W7\Core\Log\LogBuffer;
use W7\Core\Log\Logger;
use W7\Core\Log\LogManager;

class LoggerTest extends TestCase {
	protected function clearLog() {
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
		 * @var LogBuffer $handler
		 */
		$logger = \W7\Core\Facades\Logger::channel();
		$logger->bufferLimit = 5;
		$handler = $logger->getHandlers()[0];
		$handlerReflect = new \ReflectionClass($handler);
		$property = $handlerReflect->getProperty('bufferLimit');
		$property->setAccessible(true);
		$property->setValue($handler, 5);

		$logger->debug('test');
		$logger->debug('test');
		$logger->debug('test');
		$logger->debug('test');
		$logger->debug('test');
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(false, count($files) > 0);

		$logger->debug('test');
		$files = glob(RUNTIME_PATH . '/logs/w7-*.log');
		$this->assertSame(true, count($files) > 0);

		$this->clearLog();
	}

	public function testUserHandler() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(__DIR__ . '/Util/Handler/Log', APP_PATH . '/Handler/Log');

		$handler = new LogBuffer(new TestHandler(), 1, Logger::DEBUG, true, true);
		ilogger()->setHandlers([$handler]);
		ob_start();
		ilogger()->debug('test');
		$content = ob_get_clean();

		$this->assertSame('test', unserialize($content)[0]['message']);

		$filesystem->deleteDirectory(APP_PATH . '/Handler/Log');
	}

	public function testDestructFlushLog() {
		@unlink(RUNTIME_PATH . '/flush.log');

		$logger = new Logger('flush', [
			new LogBuffer(StreamHandler::getHandler([
				'path' => RUNTIME_PATH . '/flush.log',
				'level' => Logger::DEBUG,
			]), 2)
		]);
		$logger->bufferLimit = 2;

		$logger->debug('flush');
		$this->assertSame(false, file_exists(RUNTIME_PATH . '/flush.log'));

		unset($logger);
		$this->assertSame(true, file_exists(RUNTIME_PATH . '/flush.log'));
		unlink(RUNTIME_PATH . '/flush.log');
	}
}