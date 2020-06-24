<?php

namespace W7\Tests;

use FastRoute\Dispatcher\GroupCountBased;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\ArgvInput;
use W7\Console\Application;
use W7\Core\Facades\Router;
use W7\Core\Helper\FileLoader;
use W7\Core\Provider\ProviderAbstract;
use W7\Core\Provider\ProviderManager;
use W7\Core\Route\RouteMapping;
use W7\Core\View\View;

class RouteProvider extends ProviderAbstract {
	public function boot() {
	}

	/**
	 * 发布配置到app/
	 */
	public function register() {
		/**
		 * 加载该扩展包的路由
		 */
		$this->rootPath = __DIR__ . '/Util/Provider';
		$this->registerRoute('../route.php');
	}
}

class ConfigProvider extends ProviderAbstract {
	public function boot() {
	}

	/**
	 * 发布配置到app/
	 */
	public function register() {
		/**
		 * 加载该扩展包的路由
		 */
		$this->rootPath = BASE_PATH . '/';
		$this->registerConfig('server.php', 'test_server');
		$this->publishConfig('server.php', 'test_server.php');
	}
}

class CommandProvider extends ProviderAbstract {
	public function register() {
		$this->rootPath = APP_PATH ;
		$this->packageNamespace = 'W7\App\src';
		$this->registerCommand();
	}
}

class ViewProvider extends ProviderAbstract {
	public function register() {
		$this->rootPath = __DIR__ . '/Util/Provider';
		$this->registerView('provider');
	}
}


class ProviderTest extends TestCase {
	public function testMake() {
		/**
		 * @var Application $application
		 */
		$application = iloader()->singleton(Application::class);
		$command = $application->get('make:provider');
		$command->run(new ArgvInput([
			'input',
			'--name=test1'
		]), ioutputer());

		$this->assertSame(true, file_exists(APP_PATH . '/Provider/Test1Provider.php'));

		unlink(APP_PATH . '/Provider/Test1Provider.php');
	}

	public function testRoute() {
		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = iloader()->singleton(ProviderManager::class);
		$providerManager->registerProvider(RouteProvider::class, 'test');

		//route
		$routeMapping = new RouteMapping(Router::getFacadeRoot(), new FileLoader());
		$routeMapping->getMapping();
		$routeInfo = irouter()->getData();
		$dispatch = new GroupCountBased($routeInfo);
		$result = $dispatch->dispatch('POST', '/module1/setting/save1');

		$this->assertSame('test.save1', $result[1]['name']);
		$this->assertStringContainsString("\\Vendor\\Test\\", $result[1]['handler'][0]);
	}

	public function testConfig() {
		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = iloader()->singleton(ProviderManager::class);
		$providerManager->registerProvider(ConfigProvider::class, 'test');

		$config = iconfig()->getUserConfig('test_server');
		$this->assertArrayHasKey('host', $config['tcp']);
	}

	public function testPublish() {
		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = iloader()->singleton(ProviderManager::class);
		$providerManager->registerProvider(ConfigProvider::class, 'test');

		/**
		 * @var  Application $application
		 */
		$application = iloader()->get(Application::class);
		$application->get('vendor:publish')->run(new ArgvInput(
			[
				'input',
				'--provider=' . ConfigProvider::class
			]
		), ioutputer());

		$this->assertSame(true, file_exists(BASE_PATH . '/config/test_server.php'));
		unlink(BASE_PATH . '/config/test_server.php');
	}

	public function testCommand() {
		if (!is_dir(APP_PATH . '/src/Command/Provider')) {
			mkdir(APP_PATH . '/src/Command/Provider', 0777, true);
		}
		file_put_contents(APP_PATH . '/src/Command/Provider/IndexCommand.php', file_get_contents(__DIR__ . '/Util/Provider/IndexCommand.php'));

		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = iloader()->singleton(ProviderManager::class);
		$providerManager->registerProvider(CommandProvider::class, 'test');

		/**
		 * @var  Application $application
		 */
		$application = iloader()->get(Application::class);
		$command = $application->get('provider:index');

		$this->assertSame(true, $command instanceof \W7\App\src\Command\Provider\IndexCommand);

		unlink(APP_PATH . '/src/Command/Provider/IndexCommand.php');
		rmdir(APP_PATH . '/src/Command/Provider');
		rmdir(APP_PATH . '/src/Command');
		rmdir(APP_PATH . '/src');
	}

	public function testView() {
		/**
		 * @var ProviderManager $providerManager
		 */
		$providerManager = iloader()->singleton(ProviderManager::class);
		$providerManager->registerProvider(ViewProvider::class, 'test');

		$content = iloader()->singleton(View::class)->render('@provider/index');
		$this->assertSame('ok', $content);
	}
}