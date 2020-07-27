<?php

namespace W7\Tests\Database;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\SQLiteConnection;
use W7\Core\Facades\DB;
use W7\Tests\TestCase;

class SQLite3Connection extends SQLiteConnection {
	public function getPoolName() {
		$activeConnection = $this->getActiveConnection();
		if (!empty($activeConnection->poolName)) {
			return $activeConnection->poolName;
		}
		return '';
	}

	/**
	 * 获取当前活动的查询连接
	 */
	public function getActiveConnection() {
		if ($this->pdo instanceof \PDO) {
			return $this->pdo;
		} else {
			return $this->readPdo;
		}
	}
}

class DatabaseTestCase extends TestCase {
	protected function registerSqlite() {
		Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
			return new SQLite3Connection($connection, $database, $prefix, $config);
		});

		$dbconfig = iconfig()->getUserConfig('app');
		$dbconfig['database']['sqlite'] = [
			'driver' => 'sqlite',
			'database' => ':memory:',
			'host' => 'localhost',
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => 'ims_',
			'port' => '3306',
			'strict' => false
		];

		$resolver = Model::getConnectionResolver();
		$reflect = new \ReflectionClass($resolver);
		$property = $reflect->getProperty('app');
		$property->setAccessible(true);
		$config = $property->getValue($resolver);
		$config['config']['database.connections'] = $dbconfig['database'];
		$property->setValue($resolver, $config);
	}

	public function setUp(): void {
		parent::setUp();
		DB::$resolvedInstance = [];
		$this->registerSqlite();
	}
}