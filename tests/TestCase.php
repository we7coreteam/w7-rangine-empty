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
		FacadeAbstract::$resolvedInstance = [];
		App::$self = null;
	}
}