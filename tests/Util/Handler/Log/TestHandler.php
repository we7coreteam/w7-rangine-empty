<?php

namespace W7\App\Handler\Log;

use Monolog\Handler\HandlerInterface as MonologInterface;
use W7\Core\Log\Handler\HandlerAbstract;

class TestHandler extends HandlerAbstract {
	public static function getHandler($config): MonologInterface {
		return new static();
	}

	public function handleBatch(array $records) {
		echo serialize($records);
	}
}