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
		$this->addOption('--name', null, InputOption::VALUE_REQUIRED);
	}

	protected function handle($options) {
		$this->name = $options['name'];
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
}