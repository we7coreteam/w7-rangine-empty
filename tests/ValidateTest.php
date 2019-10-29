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

	public function testExtend() {
		mkdir(BASE_PATH . '/config/lang/zh-CN', 0777, true);
		copy(__DIR__ . '/Util/lang/zh-CN/validation.php', BASE_PATH . '/config/lang/zh-CN/validation.php');

		ivalidator()->extend('user_validate', function ($attribute, $value, $parameters) {
			return $value === 'test';
		});

		$data = [
			'key' => 1,
			'value' => 'test'
		];

		$result = ivalidate($data, [
			'key' => 'required',
			'value' => 'user_validate'
		], [
			'test.required' => 'test参数错误'
		]);
		$this->assertSame(1, $result['key']);
		$this->assertSame('test', $result['value']);

		$data = [
			'key' => 1,
			'value' => 'test1'
		];
		try {
			ivalidate($data, [
				'key' => 'required',
				'value' => 'user_validate'
			], [
				'test.required' => 'test参数错误'
			]);
		} catch (ValidatorException $e) {
			$this->assertSame(403, $e->getCode());
			$this->assertSame('value : 自定义验证', $e->getMessage());
		}

		unlink(BASE_PATH . '/config/lang/zh-CN/validation.php');
		rmdir(BASE_PATH . '/config/lang/zh-CN');
		rmdir(BASE_PATH . '/config/lang');
	}
}