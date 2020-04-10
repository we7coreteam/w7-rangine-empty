<?php

namespace W7\App\Exception;

use Psr\Http\Message\ResponseInterface;
use W7\Core\Exception\ResponseExceptionAbstract;

class HttpErrorException extends ResponseExceptionAbstract {
	public function render(): ResponseInterface {
	    //返回response对象
		return $this->response->withStatus($this->getCode())->withContent(['error' => $this->getMessage()]);
	}
}