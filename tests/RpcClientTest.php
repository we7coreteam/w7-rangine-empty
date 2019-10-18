<?php

namespace W7\Tests;

use W7\Rpc;

class RpcClientTest extends TestCase {
	public function testTcp() {
		$client = new Rpc([
			'base_uri' => 'tcp://127.0.0.1:9999'
		]);
		$ret = $client->post('rpc/test', [
			'key' => 'value'
		]);
		$this->assertSame('Swoole\Client::connect(): connect to server[127.0.0.1:9999] failed, Error: Connection refused[111]', $ret['error']);


		$client = new Rpc([
			'base_uri' => 'tcp://127.0.0.1:8888'
		]);
		$ret = $client->post('rpc/test', [
			'key' => 'value'
		]);
		$this->assertSame('Route not found', $ret['error']);
	}

	public function testSSL() {
		$client = new Rpc([
			'base_uri' => 'ssl://127.0.0.1:8888'
		]);
		$ret = $client->post('rpc/test', [
			'key' => 'value'
		]);
		$this->assertSame('swoole_client: Could not connect to 127.0.0.1:8888 with error code 0', $ret['error']);
	}

	public function testHttp() {
		$client = new Rpc([
			'base_uri' => 'http://127.0.0.1:9999'
		]);
		$ret = $client->get('rpc/test', [
			'key' => 'value'
		]);
		$this->assertSame('cURL error 7: Failed to connect to 127.0.0.1 port 9999: Connection refused (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)', $ret['error']);


		$client = new Rpc([
			'base_uri' => 'http://127.0.0.1:88'
		]);

		$jar = new \GuzzleHttp\Cookie\CookieJar();
		$domain = 'test.com';
		$cookies = [
			'PHPSESSID' => 'web2~ri5m4tjbi6gk6eeu72ghg27l61'
		];
		$cookieJar = $jar->fromArray($cookies, $domain);
		$ret = $client->get('rpc/test', [
			'cookies' => $cookieJar
		]);
		//http status 400
		$this->assertNotEquals('', $ret['error']);
	}

	public function testHttps() {
		$client = new Rpc([
			'base_uri' => 'https://127.0.0.1:88'
		]);
		$ret = $client->get('rpc/test', [
			'key' => 'value'
		]);
		$this->assertSame('cURL error 35: OpenSSL SSL_connect: SSL_ERROR_SYSCALL in connection to 127.0.0.1:88  (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)', $ret['error']);
	}


}