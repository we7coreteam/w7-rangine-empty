<?php

namespace W7\App\Controller;

use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class LogController extends ControllerAbstract {
	public function index(Request $request) {
		ob_start();
		$config = iconfig()->getUserConfig('log');
		$num = $config['channel']['test']['buffer_limit'] + 1;
		for ($i = 0; $i < $num; $i++)
			ilogger()->channel('test')->info('test');
		$content = ob_get_contents();

		return $this->response()->withContent($content);
	}
}