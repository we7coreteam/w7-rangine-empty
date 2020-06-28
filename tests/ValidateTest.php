<?php

namespace W7\Tests;

use Illuminate\Contracts\Validation\Rule;
use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Exception\ValidatorException;

class UserRule implements Rule {
	private $error;

	public function passes($attribute, $value) {
		if (!is_string($value)) {
			$this->error = '用户名类型错误';
			return false;
		}
		if (strlen($value) < 6) {
			$this->error = '用户名不能少于6位';
			return false;
		}
		if (strlen($value) > 10) {
			$this->error = '用户名不能多余10位';
			return false;
		}

		return true;
	}

	public function message() {
		return $this->error;
	}
}

class ValidateTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:validate');

		$command->run(new ArgvInput([
			'input',
			'--name=test'
		]), ioutputer());

		$file = APP_PATH . '/Model/Validate/TestRule.php';

		$this->assertSame(true, file_exists($file));

		unlink($file);
		rmdir(APP_PATH . '/Model/Validate');
	}

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
			$this->assertSame('{"error":"test 不能为空。"}', $e->getMessage());
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
			$this->assertSame('{"error":"test参数错误"}', $e->getMessage());
		}
	}

	public function testExtend() {
		$this->initApp();
		if (!file_exists(BASE_PATH . '/lang/zh-CN')) {
			mkdir(BASE_PATH . '/lang/zh-CN', 0777, true);
		}

		copy(__DIR__ . '/Util/lang/zh-CN/validation.php', BASE_PATH . '/lang/zh-CN/validation.php');

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
			$this->assertSame('{"error":"自定义验证"}', $e->getMessage());
		}

		unlink(BASE_PATH . '/lang/zh-CN/validation.php');
		rmdir(BASE_PATH . '/lang/zh-CN');
	}

	public function testUserRule() {
		$data = [
			'name' => '1'
		];

		try{
			ivalidate($data, [
				'name' => [new UserRule()]
			]);
		} catch (\Throwable $e) {
			$this->assertSame('{"error":"用户名不能少于6位"}', $e->getMessage());
		}

		$data = [
			'name' => '12121212111'
		];

		try{
			ivalidate($data, [
				'name' => [new UserRule()]
			]);
		} catch (\Throwable $e) {
			$this->assertSame('{"error":"用户名不能多余10位"}', $e->getMessage());
		}

		$data = [
			'name' => '12121211'
		];

		$result = ivalidate($data, [
			'name' => [new UserRule()]
		]);
		$this->assertSame('12121211', $result['name']);
	}
}