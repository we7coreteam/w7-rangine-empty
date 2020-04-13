<?php
/**
 * 测试用例父类，
 */

namespace W7\Tests;

use W7\App;

class TestCase extends \PHPUnit\Framework\TestCase {
	public function setUp() :void {
		parent::setUp();

		$this->initApp();
	}

	public function initApp() {
		require_once __DIR__ . '/../config/define.php';
		new App();
	}
}