<?php

namespace W7\App\Exception;

use W7\Core\Exception\ResponseExceptionAbstract;

class HttpErrorException extends ResponseExceptionAbstract {
	public function __construct($message = "", $code = 0, \Throwable $previous = null) {
		// 此处可以重新包装 message 数据
		$message = \json_encode(['error' => $message, 'code' => $code], JSON_UNESCAPED_UNICODE);
		parent::__construct($message, $code, $previous);
	}
}