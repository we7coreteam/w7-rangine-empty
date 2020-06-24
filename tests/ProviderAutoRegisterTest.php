<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use W7\Core\Facades\Container;
use W7\Core\Provider\ProviderManager;

class ProviderAutoRegisterTest extends TestCase {
	public function setUp(): void {

	}

	public function testAutoFind() {
		$filesystem = new Filesystem();
		$filesystem->copy(BASE_PATH . '/tests/Util/Provider/TestProvider.php', APP_PATH . '/Provider/TestProvider.php');

		$cmd = 'cd ' . BASE_PATH . '/' . ' && composer dump-autoload';
		exec($cmd);

		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = Container::get(ProviderManager::class);
		$providerManager->register();

		$reflect = new \ReflectionClass($providerManager);
		$property = $reflect->getProperty('registeredProviders');
		$property->setAccessible(true);
		$providers = $property->getValue($providerManager);

		$this->assertArrayHasKey('W7\App\Provider\TestProvider', $providers);

		$filesystem->delete(APP_PATH . '/Provider/TestProvider.php');
		exec($cmd);
	}
}