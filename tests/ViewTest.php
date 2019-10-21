<?php

namespace W7\Tests;

use W7\Core\View\View;

class ViewTest extends TestCase {
	public function testRender() {
		copy(__DIR__ . '/Provider/view/index.html', APP_PATH . '/View/test.html');

		$content = iloader()->singleton(View::class)->render('test');

		$this->assertSame('ok', $content);

		unlink(APP_PATH . '/View/test.html');
	}
}