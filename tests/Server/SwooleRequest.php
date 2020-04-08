<?php

namespace W7\Tests\Server;

use Swoole\Http\Request;

class SwooleRequest extends Request {
	public function rawContent() {
		return '';
	}
}