<?php


namespace W7\Tests;

use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;

class TaskTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:task');

		$command->run(new ArgvInput([
			'input',
			'--name=test'
		]), ioutputer());

		$file = APP_PATH . '/Task/TestTask.php';

		$this->assertSame(true, file_exists($file));

		unlink($file);
	}
}