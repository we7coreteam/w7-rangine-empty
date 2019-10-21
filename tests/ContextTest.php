<?php

namespace W7\Tests;

class ContextTest extends TestCase {
	public function testInNoCo() {
		icontext()->setContextDataByKey('test', 1);
		$this->assertSame(1, icontext()->getContextDataByKey('test'));

		icontext()->setContextDataByKey('test', null);
		$this->assertSame(null, icontext()->getContextDataByKey('test'));
	}

	public function testInCo() {
		$context = icontext();
		$context1 = icontext();

		go(function () use (&$context) {
			$context->setContextDataByKey('test', 1);
			$this->assertSame(1, $context->getContextDataByKey('test'));
			igo(function () {
				$data = icontext()->getContextDataByKey('test');
				$this->assertSame(1, $data);
				icontext()->setContextDataByKey('test', 3);
			});
			$this->assertSame(1, $context->getContextDataByKey('test'));
		});
		go(function () use (&$context1) {
			$context1->setContextDataByKey('test', 2);
			$this->assertSame(2, $context1->getContextDataByKey('test'));
		});
	}
}