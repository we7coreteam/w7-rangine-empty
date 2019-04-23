<?php
/**
 * @author donknap
 * @date 19-4-15 下午5:02
 */

namespace W7\Tests;


class PoolTest extends TestCase {

	public function setUp() : void {
		parent::setUp();

		$this->assertTrue(iconfig()->getUserAppConfig('pool')['database']['default']['enable'], 'Open the database connection pool configuration');

		$databaseConfig = iconfig()->getUserAppConfig('database')['default'] ?? [];
		$this->assertNotEmpty($databaseConfig['host'], 'Valid default database config');
		$this->assertNotEmpty($databaseConfig['database'], 'Valid default database config');
		$this->assertNotEmpty($databaseConfig['username'], 'Valid default database config');
		$this->assertNotEmpty($databaseConfig['password'], 'Valid default database config');
	}

	public function testDbPool() {
		$this->assertInstanceOf(\PDO::class, idb()->getPdo(), 'Unable to connect to the database');

		$tables = idb()->select("SHOW TABLES;");
		$tables = idb()->select("SHOW TABLES;");


		print_r($tables);
	}
}