<?php

namespace W7\Tests\Model;

use Illuminate\Database\Schema\Blueprint;
use W7\Core\Facades\DB;
use Illuminate\Support\Facades\Schema;
use W7\Core\Database\ModelAbstract;

class TestModel extends ModelAbstract {
	protected $table = 'test';
}

class Test1Model extends ModelAbstract {
	protected $connection = 'sqlite_test';
	protected $table = 'test1';
}

class TransactionTest extends ModelTestAbstract {
	public function testSimple() {
		idb()->connection()->getSchemaBuilder()->dropIfExists('test');
		idb()->connection()->getSchemaBuilder()->create('test', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$this->assertSame('sqlite_test', (new Test1Model())->getConnection()->getName());

		idb()->beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());

		$model = new TestModel();
		$model->id = 1;
		$model->name = 'test';
		$model->save();

		idb()->rollBack();

		$value = (new TestModel())->where('id', '=', 1)->first();
		$this->assertSame(null, $value);


		idb()->beginTransaction();

		$model = new TestModel();
		$model->id = 1;
		$model->name = 'test';
		$model->save();

		idb()->commit();

		$value = (new TestModel())->where('id', '=', 1)->first();
		$this->assertSame(1, $value->id);
		$this->assertSame('test', $value->name);


		idb()->transaction(function () {
			$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());

			$model = new TestModel();
			$model->id = 2;
			$model->name = 'test';
			$model->save();
		});

		$value = (new TestModel())->where('id', '=', 2)->first();
		$this->assertSame(2, $value->id);
		$this->assertSame('test', $value->name);


		try{
			idb()->transaction(function () {
				$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());

				$model = new TestModel();
				$model->id = 3;
				$model->name = 'test';
				$model->save();

				throw new \RuntimeException('rollback');
			});
		} catch (\Throwable $e) {
			$this->assertSame('rollback', $e->getMessage());
		}

		$value = (new TestModel())->where('id', '=', 3)->first();
		$this->assertSame(null, $value);

		idb()->connection()->getSchemaBuilder()->dropIfExists('test');
	}

	public function testFacade() {
		Schema::dropIfExists('test');
		Schema::create('test', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$this->assertSame(true, Schema::hasTable('test'));

		$this->assertSame('sqlite_test', (new Test1Model())->getConnection()->getName());

		DB::beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());

		$model = new TestModel();
		$model->id = 1;
		$model->name = 'test';
		$model->save();

		DB::rollBack();

		$this->assertSame('sqlite_test', (new Test1Model())->getConnection()->getName());
		$value = (new TestModel())->where('id', '=', 1)->first();
		$this->assertSame(null, $value);


		DB::beginTransaction();

		$model = new TestModel();
		$model->id = 1;
		$model->name = 'test';
		$model->save();

		DB::commit();

		$value = (new TestModel())->where('id', '=', 1)->first();
		$this->assertSame(1, $value->id);
		$this->assertSame('test', $value->name);


		DB::transaction(function () {
			$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());

			$model = new TestModel();
			$model->id = 2;
			$model->name = 'test';
			$model->save();
		});
		$this->assertSame('sqlite_test', (new Test1Model())->getConnection()->getName());

		$value = (new TestModel())->where('id', '=', 2)->first();
		$this->assertSame(2, $value->id);
		$this->assertSame('test', $value->name);


		try{
			DB::transaction(function () {
				$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());

				$model = new TestModel();
				$model->id = 3;
				$model->name = 'test';
				$model->save();

				throw new \RuntimeException('rollback');
			});
		} catch (\Throwable $e) {
			$this->assertSame('rollback', $e->getMessage());
		}
		$this->assertSame('sqlite_test', (new Test1Model())->getConnection()->getName());

		$value = (new TestModel())->where('id', '=', 3)->first();
		$this->assertSame(null, $value);

		Schema::dropIfExists('test');
	}

	public function testMulti() {
		Schema::dropIfExists('test');
		Schema::create('test', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		DB::beginTransaction();
		DB::beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
		$model = new TestModel();
		$model->id = 2;
		$model->name = 'test';
		$model->save();

		DB::commit();
		DB::rollBack();

		$value = (new TestModel())->where('id', '=', 2)->first();
		$this->assertSame(null, $value);

		DB::beginTransaction();
		DB::beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
		$model = new TestModel();
		$model->id = 3;
		$model->name = 'test';
		$model->save();

		DB::commit();
		DB::commit();

		$value = (new TestModel())->where('id', '=', 3)->first();
		$this->assertSame(3, $value->id);
		$this->assertSame('test', $value->name);

		DB::beginTransaction();
		DB::beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
		$model = new TestModel();
		$model->id = 4;
		$model->name = 'test';
		$model->save();

		DB::rollBack();
		DB::commit();

		$value = (new TestModel())->where('id', '=', 4)->first();
		$this->assertSame(null, $value);

		Schema::dropIfExists('test');
	}

	public function testConnection() {
		$this->assertSame('sqlite', DB::getDefaultConnection());


		Schema::connection('sqlite_test')->create('test1', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		Schema::dropIfExists('test1');
		DB::connection('sqlite_test')->beginTransaction();
		$model = new Test1Model();
		$model->id = 4;
		$model->name = 'test';
		$model->save();
		DB::connection('sqlite_test')->rollBack();

		$value = (new Test1Model())->where('id', '=', 4)->first();
		$this->assertSame(null, $value);

		DB::connection('sqlite_test')->beginTransaction();
		$model = new Test1Model();
		$model->id = 4;
		$model->name = 'test';
		$model->save();
		DB::connection('sqlite_test')->commit();

		$value = (new Test1Model())->where('id', '=', 4)->first();
		$this->assertSame(4, $value->id);
		$this->assertSame('test', $value->name);

		DB::setDefaultConnection('sqlite_test');
		DB::beginTransaction();
		$model = new Test1Model();
		$model->id = 5;
		$model->name = 'test';
		$model->save();
		DB::rollBack();
		DB::setDefaultConnection('sqlite');

		$value = (new Test1Model())->where('id', '=', 5)->first();
		$this->assertSame(null, $value);

		DB::setDefaultConnection('sqlite_test');
		DB::beginTransaction();
		$model = new Test1Model();
		$model->id = 5;
		$model->name = 'test';
		$model->save();
		DB::commit();
		DB::setDefaultConnection('sqlite');

		$value = (new Test1Model())->where('id', '=', 5)->first();
		$this->assertSame(5, $value->id);
		$this->assertSame('test', $value->name);

		Schema::connection('sqlite_test')->dropIfExists('test1');
	}
}