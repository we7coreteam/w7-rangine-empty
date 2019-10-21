<?php

namespace W7\App\Handler\Exception;

use Psr\Http\Message\ResponseInterface;
use W7\App;
use W7\Core\Exception\Handler\ExceptionHandler as ExceptionHandlerAbstract;
use W7\Core\Exception\ResponseExceptionAbstract;
use W7\Http\Exception\FatalException;

class ExceptionHandler extends ExceptionHandlerAbstract {
	public function handle(ResponseExceptionAbstract $e): ResponseInterface {
		if ($e instanceof FatalException) {
			return App::getApp()->getContext()->getResponse()->withContent('test');
		}

		return parent::handle($e);
	}
}