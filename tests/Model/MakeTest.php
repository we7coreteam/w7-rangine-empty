<?php

namespace W7\Tests\Model;

use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Tests\TestCase;

class MakeTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:model');
		$command->run(new ArgvInput([
			'input',
			'--name=user'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Model/Entity/User.php'));

		$command->run(new ArgvInput([
			'input',
			'--name=user/user'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Model/Entity/User/User.php'));

		unlink(APP_PATH . '/Model/Entity/User.php');
		unlink(APP_PATH . '/Model/Entity/User/User.php');
		rmdir(APP_PATH . '/Model/Entity/User');
	}
}