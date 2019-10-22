<?php

namespace W7\App\Handler\View;

use W7\Core\View\Handler\HandlerAbstract;

class TestHandler extends HandlerAbstract {
	public function registerFunction($name, \Closure $callback) {
		// TODO: Implement registerFunction() method.
	}

	public function registerConst($name, $value) {
		// TODO: Implement registerConst() method.
	}

	public function registerObject($name, $object) {
		// TODO: Implement registerObject() method.
	}

	public function render($namespace, $name, $context = []): string {
		return serialize([
			$namespace,
			$name
		]);
	}
}