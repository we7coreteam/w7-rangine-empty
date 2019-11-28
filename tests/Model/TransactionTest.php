<?php

namespace W7\Tests\Model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use W7\Core\Database\ModelAbstract;

class TestModel extends ModelAbstract {
	protected $table = 'test';
}

class Test1Model extends ModelAbstract {
	protected $connection = 'default';
	protected $table = 'test1';
}

class TransactionTest extends ModelTestAbstract {
	public function testSimple() {
		idb()->connection()->getSchemaBuilder()->create('test', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$this->assertSame('default', (new Test1Model())->getConnection()->getName());

		idb()->beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
		$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());

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
			$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());

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
				$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());

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
		Schema::create('test', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$this->assertSame(true, Schema::hasTable('test'));

		$this->assertSame('default', (new Test1Model())->getConnection()->getName());

		DB::beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
		$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());

		$model = new TestModel();
		$model->id = 1;
		$model->name = 'test';
		$model->save();

		DB::rollBack();

		$this->assertSame('default', (new Test1Model())->getConnection()->getName());
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
			$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());

			$model = new TestModel();
			$model->id = 2;
			$model->name = 'test';
			$model->save();
		});
		$this->assertSame('default', (new Test1Model())->getConnection()->getName());

		$value = (new TestModel())->where('id', '=', 2)->first();
		$this->assertSame(2, $value->id);
		$this->assertSame('test', $value->name);


		try{
			DB::transaction(function () {
				$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
				$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());

				$model = new TestModel();
				$model->id = 3;
				$model->name = 'test';
				$model->save();

				throw new \RuntimeException('rollback');
			});
		} catch (\Throwable $e) {
			$this->assertSame('rollback', $e->getMessage());
		}
		$this->assertSame('default', (new Test1Model())->getConnection()->getName());

		$value = (new TestModel())->where('id', '=', 3)->first();
		$this->assertSame(null, $value);

		Schema::dropIfExists('test');
	}

	public function testMulti() {
		Schema::create('test', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		DB::beginTransaction();
		DB::beginTransaction();

		$this->assertSame('sqlite', (new TestModel())->getConnection()->getName());
		$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());
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
		$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());
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
		$this->assertSame('sqlite', (new Test1Model())->getConnection()->getName());
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
}