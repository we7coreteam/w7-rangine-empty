<?php

namespace W7\Tests\Future;

use Symfony\Component\Console\Input\ArgvInput;
use W7\App;
use W7\Console\Application;
use W7\Core\Config\Config;
use W7\Tests\TestCase;

class ConfigCacheTest extends TestCase {
	public function testConfigCache() {
		/**
		 * @var Application $application
		 */
		$application = icontainer()->singleton(Application::class);
		$application->get('config:clear')->run(new ArgvInput([
			'test'
		]), ioutputer());
		$this->assertSame(false, file_exists(App::getApp()->getConfigCachePath()));

		$application->get('config:cache')->run(new ArgvInput([
			'test'
		]), ioutputer());
		$this->assertSame(true, file_exists(App::getApp()->getConfigCachePath()));

		rename(BASE_PATH . '/config/log.php', BASE_PATH . '/config/log1.php');
		$this->assertSame(false, file_exists(BASE_PATH . '/config/log.php'));

		iconfig()->set('app.setting.cache', 1);
		$this->assertSame(1, iconfig()->get('app.setting.cache'));

		$this->assertEmpty(iconfig()->get('log1.default', ''));
		$this->assertNotEmpty(iconfig()->get('log.default'));

		rename(BASE_PATH . '/config/log1.php', BASE_PATH . '/config/log.php');
	}
}