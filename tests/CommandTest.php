<?php

namespace W7\Tests;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use W7\App\Command\Test\IndexCommand;
use W7\Console\Application;
use W7\Console\Command\CommandAbstract;

class TestCommand extends CommandAbstract {
	public $name;

	protected function configure() {
		$this->addOption('--config-app-setting-overwrite', '-w', InputOption::VALUE_REQUIRED);
		$this->addOption('--name', null, InputOption::VALUE_REQUIRED);
	}

	protected function handle($options) {
		$this->name = $options['name'];
	}
}

class Call1Command extends CommandAbstract {
	protected function configure() {
		$this->addOption('test', null, InputOption::VALUE_REQUIRED);
	}

	protected function handle($options) {
		echo $options['test'];
	}
}

class CallCommand extends CommandAbstract {
	protected function handle($options) {
		$this->call('call:call1', [
			'--test' => '1'
		]);
	}
}

class CommandTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:command');
		$command->run(new ArgvInput([
			'input',
			'--name=test/index'
		]), ioutputer());

		$application->add(new IndexCommand('test:index'));

		$this->assertSame(true, $application->has('test:index'));

		unlink(APP_PATH . '/Command/Test/IndexCommand.php');
		rmdir(APP_PATH . '/Command/Test');
	}

	public function testRun() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = new TestCommand('test:command');
		$application->add($command);
		$application->get('test:command')->run(new ArgvInput([
			'test',
			'--name=test'
		]), ioutputer());

		$this->assertSame('test', $command->name);
	}

	public function testErrorOption() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = new TestCommand('test:command');
		$application->add($command);

		try{
			$application->get('test:command')->run(new ArgvInput([
				'test',
				'--name1=test'
			]), ioutputer());
		} catch (\Throwable $e) {
			$this->assertSame('The "--name1" option does not exist.', $e->getMessage());
		}
	}

	public function testCallCommand() {
		$application = iloader()->singleton(Application::class);
		$command = new CallCommand('call:call');
		$command1 = new Call1Command('call:call1');
		$application->add($command);
		$application->add($command1);

		ob_start();
		$application->get('call:call')->run(new ArgvInput([
			'test'
		]), ioutputer());
		$result = ob_get_clean();
		$this->assertSame('1', $result);
	}

	public function testOverwriteConfig() {
		$this->assertSame('', iconfig()->getUserConfig('app')['setting']['overwrite'] ?? '');
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = new TestCommand('test:command');
		$application->add($command);
		$application->get('test:command')->run(new ArgvInput([
			'test',
			'--name=test',
			'--config-app-setting-overwrite=1'
		]), ioutputer());

		$this->assertSame('test', $command->name);
		$this->assertSame('1', iconfig()->getUserConfig('app')['setting']['overwrite']);
	}

	public function testAuthFindCommand() {
		$application = new Application();
		$reflect = new \ReflectionClass($application);
		$method = $reflect->getMethod('findCommands');
		$method->setAccessible(true);
		$commands = $method->invoke($application, __DIR__ . '/Util/Command', 'W7\Test', null);

		$this->assertSame(true, file_exists(__DIR__ . '/Util/Command/TestCommand.php'));
		$this->assertSame(true, file_exists(__DIR__ . '/Util/Command/Test/Command.php'));
		$this->assertSame(true, file_exists(__DIR__ . '/Util/Command/Test/IndexCommand.php'));

		$this->assertSame(1, count($commands));
		$this->assertSame('test:index', array_keys($commands)[0]);
		$this->assertSame('W7\Test\Command\Test\IndexCommand', $commands['test:index']);
	}
}