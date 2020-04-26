<?php

namespace W7\Tests;

class CoroutineTest extends TestCase {
	public function testIgoInFpm() {
		igo(function () {
			file_put_contents(__DIR__ . '/igo.txt', 1);
		});

		register_shutdown_function(function () {
			$this->assertSame(true, file_exists(__DIR__ . '/igo.txt'));
			unlink(__DIR__ . '/igo.txt');
		});
	}
}