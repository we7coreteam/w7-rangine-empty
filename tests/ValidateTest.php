<?php

namespace W7\Tests;

use W7\Core\Exception\ValidatorException;

class ValidateTest extends TestCase {
	public function testValidate() {
		$data = [
			'key' => 1,
			'value' => 2
		];

		$result = ivalidate($data, [
			'key' => 'required',
			'value' => 'required'
		]);

		$this->assertSame(1, $result['key']);
		$this->assertSame(2, $result['value']);

		try {
			ivalidate($data, [
				'key' => 'required',
				'value' => 'required',
				'test' => 'required'
			]);
		} catch (ValidatorException $e) {
			$this->assertSame(403, $e->getCode());
			$this->assertSame('test : test 不能为空。', $e->getMessage());
		}
	}

	public function testMessage() {
		$data = [
			'key' => 1,
			'value' => 2
		];
		try {
			ivalidate($data, [
				'key' => 'required',
				'value' => 'required',
				'test' => 'required'
			], [
				'test.required' => 'test参数错误'
			]);
		} catch (ValidatorException $e) {
			$this->assertSame(403, $e->getCode());
			$this->assertSame('test : test参数错误', $e->getMessage());
		}
	}
}