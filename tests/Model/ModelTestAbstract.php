<?php

namespace W7\Tests\Model;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use W7\Tests\Database\DatabaseTestCase;
use W7\Tests\Database\SQLite3Connection;

abstract class ModelTestAbstract extends DatabaseTestCase {
	protected function registerSqlite() {
		Connection::resolverFor('sqlite', function ($connection, $database, $prefix, $config) {
			return new SQLite3Connection($connection, $database, $prefix, $config);
		});

		$dbconfig = iconfig()->getUserConfig('app');
		$dbconfig['database']['sqlite'] = [
			'driver' => 'sqlite',
			'database' => __DIR__ . '/Data/test.db',
			'host' => 'localhost',
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => 'ims_',
			'port' => '3306',
			'strict' => false
		];
		$dbconfig['database']['sqlite_test'] = [
			'driver' => 'sqlite',
			'database' => __DIR__ . '/Data/test1.db',
			'host' => 'localhost',
			'username' => 'root',
			'password' => '123456',
			'charset' => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix' => 'ims_',
			'port' => '3306',
			'strict' => false
		];
		$dbconfig['database']['mysql'] = [
			'driver' => 'mysql',
			'database' => __DIR__ . '/Data/test1.db',
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


		idb()->setDefaultConnection('sqlite');
	}
}