<?php
/**
 * 测试用例父类，
 */

namespace W7\Tests;

use Illuminate\Container\Container;
use W7\App;
use W7\Core\Facades\FacadeAbstract;

class TestCase extends \PHPUnit\Framework\TestCase {
	public function setUp() :void {
		parent::setUp();

		$this->initApp();
	}

	public function initApp() {
		require_once __DIR__ . '/../config/define.php';
		new App();
		//这里加清空的原因是，如果多个测试用例同时运行，如果使用门面，会有实例无法释放问题
		FacadeAbstract::$resolvedInstance = [];
		App::$self = null;
	}
}