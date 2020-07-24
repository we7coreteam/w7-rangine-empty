<?php

namespace W7\Tests\Future;

use W7\App;
use W7\Command\ServiceProvider;
use W7\Console\Application;
use W7\Core\Bootstrap\ProviderBootstrap;
use W7\Core\Facades\Container;
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

class DeferProvider1 extends ProviderAbstract {
	public function register() {
		$this->container->set('test2', DeferService::class);
	}

	public function providers(): array {
		return ['test2'];
	}
}

class ParentDeferProvider extends ProviderAbstract {
	public function providers(): array {
		return [DeferProvider1::class];
	}
}

class DeferProviderTest extends TestCase {
	public function testRequestProvider() {
		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = Container::get(ProviderManager::class);

		$this->assertSame(false, $providerManager->hasRegister(ServiceProvider::class));
		$this->assertSame(false, $providerManager->hasRegister(\W7\Crontab\ServiceProvider::class));
		$this->assertSame(false, $providerManager->hasRegister(\W7\DatabaseTool\ServiceProvider::class));

		$this->assertSame(false, $providerManager->hasRegister(ValidateProvider::class));
	}

	public function testCommandProvider() {
		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = Container::get(ProviderManager::class);

		$this->assertSame(false, $providerManager->hasRegister(ServiceProvider::class));

		icontainer()->get(Application::class);

		$this->assertSame(true, $providerManager->hasRegister(ServiceProvider::class));
	}

	public function testRegisterProviders() {
		$providers = iconfig()->get('provider', []);
		$providers[DeferProvider::class] = [DeferProvider::class];

		$providerManager = new ProviderManager(App::getApp()->getContainer());
		$providerManager->register($providers);

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider::class));

		$providerManager->registerProvider(DeferProvider::class);

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider::class));

		$providerManager->registerProvider(DeferProvider::class, DeferProvider::class, true);

		$this->assertSame(true, $providerManager->hasRegister(DeferProvider::class));
	}

	public function testTriggerRegisterProvider() {
		$providers = iconfig()->get('provider', []);
		$providers[DeferProvider::class] = [DeferProvider::class];
		DeferProvider::$registerNum = 0;

		$providerManager = new ProviderManager(App::getApp()->getContainer());
		$providerManager->register($providers);

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider::class));

		$instance = icontainer()->get('test');
		$this->assertSame(true, $instance instanceof DeferService);
		$this->assertSame(1, DeferProvider::$registerNum);

		icontainer()->get('test1');
		$this->assertSame(1, DeferProvider::$registerNum);

		$this->assertSame(true, $providerManager->hasRegister(DeferProvider::class));
	}

	public function testTriggerMultiRegisterProvider() {
		$providers = iconfig()->get('provider', []);
		$providers[DeferProvider1::class] = [DeferProvider1::class];
		$providers[ParentDeferProvider::class] = [ParentDeferProvider::class];
		DeferProvider::$registerNum = 0;

		$providerManager = new ProviderManager(App::getApp()->getContainer());
		$providerManager->register($providers);

		$this->assertSame(false, $providerManager->hasRegister(DeferProvider1::class));
		$this->assertSame(false, $providerManager->hasRegister(ParentDeferProvider::class));

		icontainer()->get('test11');
		$this->assertSame(false, $providerManager->hasRegister(DeferProvider1::class));
		$this->assertSame(false, $providerManager->hasRegister(ParentDeferProvider::class));

		$instance = icontainer()->get('test2');
		$this->assertSame(true, $instance instanceof DeferService);

		$this->assertSame(true, $providerManager->hasRegister(DeferProvider1::class));
		$this->assertSame(true, $providerManager->hasRegister(ParentDeferProvider::class));
	}
}