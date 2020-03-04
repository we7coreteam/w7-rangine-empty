<?php

namespace W7\App\Controller\Home;

use GuzzleHttp\Client;
use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class ClientController extends ControllerAbstract
{
	public function server(Request $request) {
		return [$request->post(), $request->getQueryParams()];
	}

	public function http(Request $request) {
		$client = new Client();
		$response = $client->post('http://127.0.0.1:88/server?re=asd&sd=34', [
			'form_params' => [
				'test' => 1
			]
		]);
		return $this->responseHtml($response->getBody()->getContents());
	}

	public function tcp(Request $request) {
		$client = new Client();
		$response = $client->post('http://127.0.0.1:88/server?re=asd&sd=34', [
			'form_params' => [
				'test' => 1
			],
			'protocol' => 'tcp'
		]);
		return $this->responseHtml($response->getBody()->getContents());
	}
}