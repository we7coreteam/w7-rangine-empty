<?php
/**
 * 测试用例父类，注册数据库
 */

namespace W7\Tests;



use Illuminate\Container\Container;
use Illuminate\Database\Connection;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Fluent;
use W7\Core\Database\Connection\PdoMysqlConnection;
use W7\Core\Database\Connection\SwooleMySqlConnection;
use W7\Core\Database\ConnectorManager;
use W7\Core\Database\DatabaseManager;

class TestCase extends \PHPUnit\Framework\TestCase {
	protected $app;

	public function setUp() :void {
		parent::setUp();

		putenv('ENV_NAME=');

		$this->registerDb();
	}

	private function registerDb() {
		//新增swoole连接mysql的方式
		Connection::resolverFor('swoolemysql', function ($connection, $database, $prefix, $config) {
			return new SwooleMySqlConnection($connection, $database, $prefix, $config);
		});
		Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
			return new PdoMysqlConnection($connection, $database, $prefix, $config);
		});

		//新增swoole连接Mysql的容器
		$container = new Container();
		//$container->instance('db.connector.swoolemysql', new SwooleMySqlConnector());
		//$container->instance('db.connector.mysql', new PdoMySqlConnector());
		$container->instance('db.connector.swoolemysql', new ConnectorManager());
		$container->instance('db.connector.mysql', new ConnectorManager());

		//侦听sql执行完后的事件，回收$connection
		$dbDispatch = new Dispatcher($container);
		$dbDispatch->listen(QueryExecuted::class, function ($data) use ($container) {
			$connection = $data->connection;
			ilogger()->channel('database')->debug($data->sql . ', params: ' . implode(',', $data->bindings));

			$poolName = $connection->getPoolName();
			if (empty($poolName)) {
				return true;
			}
			list($poolType, $poolName) = explode(':', $poolName);
			if (empty($poolType)) {
				$poolType = 'swoolemysql';
			}

			$activePdo = $connection->getActiveConnection();
			if (empty($activePdo)) {
				return false;
			}
			$connectorManager = $container->make('db.connector.' . $poolType);
			$pool = $connectorManager->getCreatedPool($poolName);
			if (empty($pool)) {
				return true;
			}
			$pool->releaseConnection($activePdo);
			return true;
		});

		$container->instance('events', $dbDispatch);

		//添加配置信息到容器
		$dbconfig = \iconfig()->getUserAppConfig('database');

		$container->instance('config', new Fluent());
		$container['config']['database.default'] = 'default';
		$container['config']['database.connections'] = $dbconfig;
		$factory = new ConnectionFactory($container);
		$dbManager = new DatabaseManager($container, $factory);

		Model::setConnectionResolver($dbManager);
		return true;
	}
}