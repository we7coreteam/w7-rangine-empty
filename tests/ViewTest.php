<?php

namespace W7\Tests;

use Illuminate\Filesystem\Filesystem;
use W7\App\Handler\View\TestHandler;
use W7\Core\View\Handler\TwigHandler;
use W7\Core\View\View;

class ViewTest extends TestCase {
	public function testRender() {
		copy(__DIR__ . '/Util/Provider/view/index.html', APP_PATH . '/View/test.html');

		$content = (new View([
			'debug' => false
		]))->render('test');

		$this->assertSame('ok', $content);

		unlink(APP_PATH . '/View/test.html');
	}

	public function testNamespace() {
		$config = [
			'debug' => false,
			'template_path' => [
				'test' => __DIR__ . '/Util/Provider/view'
			]
		];

		$view = new View($config);

		$content = $view->render('@test/index');

		$this->assertSame('ok', $content);
	}

	public function testHandler() {
		$filesystem = new Filesystem();
		$filesystem->copyDirectory(__DIR__ . '/Util/Handler/View', APP_PATH . '/Handler/View');
		$view = new View([
			'debug' => false,
			'handler' => TestHandler::class
		]);
		$content = $view->render('index');

		$this->assertSame('a:2:{i:0;s:8:"__main__";i:1;s:10:"index.html";}', $content);

		$filesystem->deleteDirectory(APP_PATH . '/Handler/View');
	}
}