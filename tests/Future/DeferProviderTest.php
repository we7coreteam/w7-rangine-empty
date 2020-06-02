<?php

namespace W7\Tests\Future;

use W7\Command\ServiceProvider;
use W7\Console\Application;
use W7\Core\Provider\ProviderAbstract;
use W7\Core\Provider\ProviderManager;
use W7\Core\Provider\ValidateProvider;
use W7\Tests\TestCase;

class DeferService {

}

class DeferProvider extends ProviderAbstract {
	public static $registerNum = 0;

	public function register() {
		++self::$registerNum;
		$this->container->set('test', DeferService::class);
	}

	public function providers(): array {
		return ['test', 'test1'];
	}
}

class DeferProviderTest extends TestCase {
	public function testRequestProvider() {
		$providerManager = new ProviderManager();
		$providerManager->register();

		$this->assertSame(false, $providerManager->hasRegister(ServiceProvider::class));
		$this->assertSame(false, $providerManager->hasRegister(\W7\Crontab\ServiceProvider::class));
		$this->assertSame(false, $providerManager->hasRegister(\W7\DatabaseTool\ServiceProvider::class));

		$this->assertSame(true, $providerManager->hasRegister(ValidateProvider::class));
	}

	public function testCommandProvider() {
		$providerManager = new ProviderManager();
		$providerManager->register();

		$this->assertSame(false, $providerManager->hasRegister(ServiceProvider::class));

		icontainer()->get(Application::class);

		$this->assertSame(true, $providerManager->hasRegister(ServiceProvider::class));
	}

	public function testRegisterProviders() {
		$providers = iconfig()->get('provider.providers', []);
		$providers[DeferProvider::class] = [DeferProvider::class];
		iconfig()->set('provider.providers', $providers);

		$providerManager = new ProviderManager();
		$providerManager->register();

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider::class));

		$providerManager->registerProvider(DeferProvider::class);

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider::class));

		$providerManager->registerProvider(DeferProvider::class, DeferProvider::class, true);

		$this->assertSame(true, $providerManager->hasRegister(DeferProvider::class));
	}

	public function testTriggerRegisterProvider() {
		$providers = iconfig()->get('provider.providers', []);
		$providers[DeferProvider::class] = [DeferProvider::class];
		iconfig()->set('provider.providers', $providers);
		DeferProvider::$registerNum = 0;

		$providerManager = new ProviderManager();
		$providerManager->register();

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider::class));

		$instance = icontainer()->get('test');
		$this->assertSame(true, $instance instanceof DeferService);
		$this->assertSame(1, DeferProvider::$registerNum);

		icontainer()->get('test1');
		$this->assertSame(1, DeferProvider::$registerNum);

		$this->assertSame(true, $providerManager->hasRegister(DeferProvider::class));
	}
}