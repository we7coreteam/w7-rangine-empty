<?php

namespace W7\App\Controller\Home;

use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;
use W7\RpcClient\Rpc;

class RpcController extends ControllerAbstract {
	private $rpc;

	public function __construct()
	{
		$this->rpc = new Rpc([
			'base_uri' => 'json://127.0.0.1:99'
		]);
	}

	public function server(Request $request) {
		return $request->post();
	}

	public function client(Request $request) {
		$result = $this->rpc->post('/rpc-server', [
			'test' => 1
		]);

		return $result;
	}
}