<?php

namespace W7\Tests\Database\Migration;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Tests\Database\DatabaseTestCase;

class MigrationTest extends DatabaseTestCase {
	private function addMigrates() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->get(Application::class);
		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_user'
		]));

		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_fans'
		]));

		$this->composerUpdate();
	}

	private function addMigrateForRollback() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->get(Application::class);
		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_rollback_user'
		]));

		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_rollback_fans'
		]));

		$this->composerUpdate();
	}

	private function addMigrateForRefresh() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->get(Application::class);
		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_refresh_user'
		]));

		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_refresh_fans'
		]));

		$this->composerUpdate();
	}

	private function addMigrateForReset() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->get(Application::class);
		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_reset_user'
		]));

		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_reset_fans'
		]));

		$this->composerUpdate();
	}

	private function addMigrateForPretend() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->get(Application::class);
		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_pretend_user'
		]));

		$application->run(new ArgvInput([
			'migrate:make',
			'migrate:make',
			'create_pretend_fans'
		]));

		$this->composerUpdate();
	}

	public function testMigrate() {
		$this->addMigrates();
		/**
		 * @var Application $application
		 */
		try {
			$connection = idb()->connection('sqlite');
			icontext()->setContextDataByKey('db-transaction', $connection);
			$application = iloader()->get(Application::class);
			$application->run(new ArgvInput([
				'migrate:migrate',
				'migrate:migrate',
				'--database=sqlite'
			]));
			$isExists = $connection->table('migration')->exists();
			$this->assertSame(true, $isExists);

			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_user', $tables));
			$this->assertSame(true, in_array('ims_fans', $tables));
		} catch (\Throwable $e) {
			//
		} finally {
			$filesystem = new Filesystem();
			$files = glob(BASE_PATH . '/database/migrations/*.php');
			$filesystem->delete($files);

			$this->composerUpdate();
		}
	}

	public function testRollback() {
		$this->addMigrateForRollback();
		/**
		 * @var Application $application
		 */
		try {
			$connection = idb()->connection('sqlite');
			icontext()->setContextDataByKey('db-transaction', $connection);
			$application = iloader()->get(Application::class);
			$application->run(new ArgvInput([
				'migrate:migrate',
				'migrate:migrate',
				'--database=sqlite'
			]));
			$isExists = $connection->table('migration')->exists();
			$this->assertSame(true, $isExists);

			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_rollback_user', $tables));
			$this->assertSame(true, in_array('ims_rollback_fans', $tables));


			$application->run(new ArgvInput([
				'migrate:make',
				'migrate:rollback',
				'--database=sqlite'
			]));
			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_migration', $tables));
			$this->assertSame(false, in_array('ims_rollback_user', $tables));
			$this->assertSame(false, in_array('ims_rollback_fans', $tables));
		} catch (\Throwable $e) {
			//
		} finally {
			$filesystem = new Filesystem();
			$files = glob(BASE_PATH . '/database/migrations/*.php');
			$filesystem->delete($files);

			$this->composerUpdate();
		}
	}

	public function testReFresh() {
		$this->addMigrateForRefresh();
		/**
		 * @var Application $application
		 */
		try {
			$connection = idb()->connection('sqlite');
			icontext()->setContextDataByKey('db-transaction', $connection);
			$application = iloader()->get(Application::class);
			$application->run(new ArgvInput([
				'migrate:migrate',
				'migrate:migrate',
				'--database=sqlite'
			]));
			$isExists = $connection->table('migration')->exists();
			$this->assertSame(true, $isExists);

			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_refresh_user', $tables));
			$this->assertSame(true, in_array('ims_refresh_fans', $tables));


			$application->run(new ArgvInput([
				'migrate:make',
				'migrate:refresh',
				'--database=sqlite'
			]));
			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_migration', $tables));
			$this->assertSame(true, in_array('ims_refresh_user', $tables));
			$this->assertSame(true, in_array('ims_refresh_fans', $tables));
		} catch (\Throwable $e) {
			//
		} finally {
			$filesystem = new Filesystem();
			$files = glob(BASE_PATH . '/database/migrations/*.php');
			$filesystem->delete($files);

			$this->composerUpdate();
		}
	}

	public function testReset() {
		$this->addMigrateForReset();
		/**
		 * @var Application $application
		 */
		try {
			$connection = idb()->connection('sqlite');
			icontext()->setContextDataByKey('db-transaction', $connection);
			$application = iloader()->get(Application::class);
			$application->run(new ArgvInput([
				'migrate:migrate',
				'migrate:migrate',
				'--database=sqlite'
			]));
			$isExists = $connection->table('migration')->exists();
			$this->assertSame(true, $isExists);

			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_reset_user', $tables));
			$this->assertSame(true, in_array('ims_reset_fans', $tables));

			$application->run(new ArgvInput([
				'migrate:reset',
				'migrate:reset',
				'--database=sqlite'
			]));
			$tables = $connection->getDoctrineSchemaManager()->listTableNames();
			$this->assertSame(true, in_array('ims_migration', $tables));
			$this->assertSame(false, in_array('ims_reset_user', $tables));
			$this->assertSame(false, in_array('ims_reset_fans', $tables));
		} catch (\Throwable $e) {
			//
		} finally {
			$filesystem = new Filesystem();
			$files = glob(BASE_PATH . '/database/migrations/*.php');
			$filesystem->delete($files);

			$this->composerUpdate();
		}
	}

	public function testPretend() {
		$this->addMigrateForPretend();
		try {
			$connection = idb()->connection('sqlite');
			icontext()->setContextDataByKey('db-transaction', $connection);
			$application = iloader()->get(Application::class);
			$application->run(new ArgvInput([
				'migrate:migrate',
				'migrate:migrate',
				'--database=sqlite',
				'--pretend'
			]));
			$tables = $connection->getDoctrineSchemaManager()->listTableNames();;
			$this->assertSame(true, in_array('ims_migration', $tables));
			$this->assertSame(false, in_array('ims_pretend_user', $tables));
			$this->assertSame(false, in_array('ims_pretend_fans', $tables));
		} catch (\Throwable $e) {
			//
		} finally {
			$filesystem = new Filesystem();
			$files = glob(BASE_PATH . '/database/migrations/*.php');
			$filesystem->delete($files);

			$this->composerUpdate();
		}
	}

	private function composerUpdate() {
		exec('cd ../../../../ && composer dump-autoload');
	}
}