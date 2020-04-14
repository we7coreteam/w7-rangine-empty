<?php

namespace W7\Tests\Model;

use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;

class MakeTest extends ModelTestAbstract {
	public function setUp(): void {
		parent::setUp();
		$tableSql = <<<EOF
CREATE TABLE IF NOT EXISTS `ims_core_log` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `channel` varchar(30) NOT NULL,
  `level` INTEGER NOT NULL,
  `created_at` INTEGER NOT NULL
);
EOF;

		idb()->connection('sqlite')->statement($tableSql);
	}

	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:model');

		$command->run(new ArgvInput([
			'input',
			'--table=core_log',
			'--connection=sqlite'
		]), ioutputer());

		$file = APP_PATH . '/Model/Entity/Core/Log.php';
		$this->assertFileExists($file);
		$this->assertStringContainsString("\$primaryKey = 'id'", file_get_contents($file));
		$this->assertStringContainsString("\$fillable = ['channel', 'level', 'created_at']", file_get_contents($file));

		unlink($file);
		rmdir(pathinfo($file, PATHINFO_DIRNAME));

		$command->run(new ArgvInput([
			'input',
			'--name=user',
			'--table=core_log',
			'--connection=sqlite'
		]), ioutputer());

		$file = APP_PATH . '/Model/Entity/User.php';
		$this->assertFileExists($file);
		unlink($file);

		$command->run(new ArgvInput([
			'input',
			'--name=test/user'
		]), ioutputer());

		$file = APP_PATH . '/Model/Entity/Test/User.php';
		$this->assertFileExists($file);
		$this->assertStringContainsString("\$primaryKey = ''", file_get_contents($file));

		unlink($file);
		rmdir(pathinfo($file, PATHINFO_DIRNAME));
	}
}