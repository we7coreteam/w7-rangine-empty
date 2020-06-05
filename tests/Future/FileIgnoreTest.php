<?php

namespace W7\Tests\Future;

use W7\Core\Helper\FileLoader;
use W7\Tests\TestCase;

class FileIgnoreTest extends TestCase {
	public function testIgnore() {
		iconfig()->set('app.setting.file_ignore', ['tests/Util/lang/zh-CN/test.php']);
		$fileLoader = new FileLoader();
		$config = $fileLoader->loadFile(BASE_PATH . '/tests/Util/lang/zh-CN/test.php');

		$this->assertEmpty($config);

		iconfig()->set('app.setting.file_ignore', ['tests/Util/lang/zh-CN/*.php']);
		$fileLoader = new FileLoader();
		$config = $fileLoader->loadFile(BASE_PATH . '/tests/Util/lang/zh-CN/test.php');
		$this->assertEmpty($config);
		$config = $fileLoader->loadFile(BASE_PATH . '/tests/Util/lang/zh-CN/validation.php');
		$this->assertEmpty($config);


		iconfig()->set('app.setting.file_ignore', ['!tests/Util/lang/zh-CN']);
		$fileLoader = new FileLoader();
		$config = $fileLoader->loadFile(BASE_PATH . '/tests/Util/lang/zh-CN/test.php');
		$this->assertIsArray($config);
		$config = $fileLoader->loadFile(BASE_PATH . '/tests/Util/lang/zh-CN/validation.php');
		$this->assertIsArray($config);
	}
}