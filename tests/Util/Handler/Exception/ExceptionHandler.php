<?php

namespace W7\App\Handler\Exception;

use W7\App;
use W7\Http\Message\Server\Response;

class ExceptionHandler extends \W7\Core\Exception\Handler\ExceptionHandler {
	public function handle(\Throwable $e): Response {
		return App::getApp()->getContext()->getResponse()->withContent('user exception handler');
	}
}