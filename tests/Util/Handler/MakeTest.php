<?php


namespace W7\Tests;


use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;

class MakeTest extends TestCase {

	protected $supportTypeWithName = ['session', 'log', 'cache', 'view'];

	public function testMakeWithName() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:handler');

		foreach ($this->supportTypeWithName as $type) {
			$command->run(new ArgvInput([
				'input',
				'--name=testMake',
				'--type=' . $type
			]), ioutputer());

			$file = APP_PATH . '/Handler/' . ucfirst($type) . '/TestMakeHandler.php';

			$this->assertSame(true, file_exists($file));

			unlink($file);
		}
	}

	public function testMakeException() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:handler');

		$command->run(new ArgvInput([
			'input',
			'--type=exception'
		]), ioutputer());

		$file = APP_PATH . '/Handler/Exception/ExceptionHandler.php';

		$this->assertSame(true, file_exists($file));

		unlink($file);
		rmdir(pathinfo($file, PATHINFO_DIRNAME));
	}
}