<?php

namespace W7\App\Controller;

use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;
use W7\Rpc;

class RpcController extends ControllerAbstract {
	private $client;
	public function __construct() {
		$client1 = new Rpc([
			'base_uri' => 'tcp://127.0.0.1:8888'
		]);
		$client2 = new Rpc([
			'base_uri' => 'tcp-json://127.0.0.1:8888'
		]);
		$client3 = new Rpc([
			'base_uri' => 'ssl://127.0.0.1:8888',

		]);
		$client4 = new Rpc([
			'base_uri' => 'ssl-json://127.0.0.1:8888'
		]);
		$client = new Rpc([
			'base_uri' => 'https://127.0.0.1:88',
			'verify' => '/full/path/to/cert.pem'
		]);
		$this->client = new Rpc([
			'base_uri' => 'http://127.0.0.1:88'
		]);
	}

	/**
	 * post get delete put options
	 * @param Request $request
	 * @return mixed
	 */
	public function index(Request $request) {
		$ret = $this->client->post('/rpc/rpc', ['key' => 'value']);
		$ret = $this->client->get('/rpc/rpc');
		$ret = $this->client->put('/rpc/rpc', ['key' => 'value']);
		return $ret;
	}

	public function rpc(Request $request) {
		return $request->post();
	}
}