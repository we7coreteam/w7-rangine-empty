<?php
/**
 * @author donknap
 * @date 18-11-6 上午9:57
 */

namespace W7\App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use W7\Core\Middleware\MiddlewareAbstract;

class TestMiddleware extends MiddlewareAbstract {
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
		//这里是中间件一些代码

		return $handler->handle($request);
	}
}