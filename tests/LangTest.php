<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;

class LangTest extends TestCase {
	private function init() {
		$config = iconfig()->getUserConfig('app');
		$config['setting']['lang'] = 'zh-CN';
		iconfig()->setUserConfig('app', $config);
	}

	public function testTrans() {
		$this->init();

		$result = itrans('validation.accepted');
		$this->assertSame('您必须接受 :attribute。', $result);

		$result = itrans('validation.between.numeric');
		$this->assertSame(':attribute 必须介于 :min - :max 之间。', $result);

		$result = itrans('passwords.password');
		$this->assertSame('密码至少是八位字符并且应与确认密码匹配。', $result);
	}

	public function testUserTrans() {
		$this->init();

		if (!file_exists(BASE_PATH . '/lang/zh-CN')) {
			mkdir(BASE_PATH . '/lang/zh-CN', 0777, true);
		}

		copy(__DIR__ . '/Util/lang/zh-CN/test.php', BASE_PATH . '/lang/zh-CN/test.php');

		$result = itrans('test.test');
		$this->assertSame('我是测试', $result);

		$result = itrans('test.group.test');
		$this->assertSame('我是分组测试', $result);

		unlink(BASE_PATH . '/lang/zh-CN/test.php');
		$filesystem = new Filesystem();
		$filesystem->deleteDirectory(BASE_PATH . '/lang/zh-CN/');
	}
}