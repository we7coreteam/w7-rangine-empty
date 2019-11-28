<?php

namespace W7\Tests\Model;
use Illuminate\Database\Schema\Blueprint;
use W7\Core\Database\ModelAbstract;

class First extends ModelAbstract {
	protected $table = 'first';
}

class QueryTest extends ModelTestAbstract {
	public function testSave() {
		idb()->connection()->getSchemaBuilder()->create('first', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$data = [
			[
				'id' => 1,
				'name' => 'test'
			],
			[
				'id' => 2,
				'name' => 'test'
			],
			[
				'id' => 3,
				'name' => 'test'
			]
		];
		foreach ($data as $item) {
			$model = new First();
			$model->id = $item['id'];
			$model->name = $item['name'];
			$model->save();
		}

		$value = (new First())->where('id', '=', 1)->first();
		$this->assertSame(1, $value->id);
		$this->assertSame('test', $value->name);

		idb()->connection()->getSchemaBuilder()->dropIfExists('first');
	}

	public function testDelete() {
		idb()->connection()->getSchemaBuilder()->create('first', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$data = [
			[
				'id' => 1,
				'name' => 'test'
			],
			[
				'id' => 2,
				'name' => 'test'
			],
			[
				'id' => 3,
				'name' => 'test'
			]
		];
		foreach ($data as $item) {
			$model = new First();
			$model->id = $item['id'];
			$model->name = $item['name'];
			$model->save();
		}

		$value = (new First())->where('id', '=', 1)->first();
		$this->assertSame(1, $value->id);
		$this->assertSame('test', $value->name);

		(new First())->where('id', '=', 1)->delete();
		$value = (new First())->where('id', '=', 1)->first();
		$this->assertSame(null, $value);

		$value = (new First())->where('id', '=', 2)->first();
		$this->assertSame(2, $value->id);
		$this->assertSame('test', $value->name);

		idb()->connection()->getSchemaBuilder()->dropIfExists('first');
	}

	public function testFirst() {
		idb()->connection()->getSchemaBuilder()->create('first', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		idb()->table('first')->insert([
			[
				'id' => 1,
				'name' => 'test'
			],
			[
				'id' => 2,
				'name' => 'test'
			],
			[
			'id' => 3,
			'name' => 'test'
			]
		]);

		$value = (new First())->where('id', '=', 1)->first();
		$this->assertSame(1, $value->id);
		$this->assertSame('test', $value->name);

		idb()->connection()->getSchemaBuilder()->dropIfExists('first');
	}

	public function testAll() {
		idb()->connection()->getSchemaBuilder()->create('first', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		idb()->table('first')->insert([
			[
				'id' => 1,
				'name' => 'test'
			],
			[
				'id' => 2,
				'name' => 'test'
			],
			[
				'id' => 3,
				'name' => 'test'
			]
		]);

		$value = (new First())->all()->toArray();
		$this->assertSame(3, count($value));
		$this->assertSame(1, $value[0]['id']);
		$this->assertSame(3, $value[2]['id']);

		idb()->connection()->getSchemaBuilder()->dropIfExists('first');
	}

	public function testPage() {
		idb()->connection()->getSchemaBuilder()->create('first', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		idb()->table('first')->insert([
			[
				'id' => 1,
				'name' => 'test'
			],
			[
				'id' => 2,
				'name' => 'test'
			],
			[
				'id' => 3,
				'name' => 'test'
			],
			[
				'id' => 4,
				'name' => 'test'
			],
			[
				'id' => 5,
				'name' => 'test'
			],
			[
				'id' => 6,
				'name' => 'test'
			]
		]);

		$value = First::query()->take(2)->skip(0)->get()->toArray();
		$this->assertSame(2, count($value));
		$this->assertSame(1, $value[0]['id']);
		$this->assertSame(2, $value[1]['id']);

		$value = First::query()->take(2)->skip(2)->get()->toArray();
		$this->assertSame(2, count($value));
		$this->assertSame(3, $value[0]['id']);
		$this->assertSame(4, $value[1]['id']);

		idb()->connection()->getSchemaBuilder()->dropIfExists('first');

		$this->assertSame(false, idb()->connection()->getSchemaBuilder()->hasTable('first'));
	}
}