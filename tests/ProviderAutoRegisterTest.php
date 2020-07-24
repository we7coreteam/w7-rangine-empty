<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use W7\Core\Bootstrap\LoadConfigBootstrap;

class ProviderAutoRegisterTest extends TestCase {
	public function setUp(): void {

	}

	public function testAutoFind() {
		$filesystem = new Filesystem();
		$filesystem->copy(BASE_PATH . '/tests/Util/Provider/TestProvider.php', APP_PATH . '/Provider/TestProvider.php');

		$cmd = 'cd ' . BASE_PATH . '/' . ' && composer dump-autoload';
		exec($cmd);

		$path = (new LoadConfigBootstrap())->getBuiltInConfigPath() . '/provider.php';

		$providers = include_once $path;

		$this->assertArrayHasKey('W7\App\Provider\TestProvider', $providers);

		$filesystem->delete(APP_PATH . '/Provider/TestProvider.php');
		exec($cmd);
	}
}