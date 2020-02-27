<?php

namespace W7\App\Controller\Home;

use GuzzleHttp\Client;
use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class RpcController extends ControllerAbstract {
	public function server(Request $request) {
		return $request->post();
	}

	public function client(Request $request) {
		$client = new Client();
		$response = $client->post('http://127.0.0.1:99/rpc-server?re=asd&sd=34', [
			'form_params' => [
				'test' => 1
			],
			'protocol' => 'tcp'
		]);
		return $this->responseHtml($response->getBody()->getContents());
	}
}