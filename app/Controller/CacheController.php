<?php


namespace W7\App\Controller;


use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class CacheController extends ControllerAbstract {
	public function set(Request $request) {
		icache()->channel('test')->set('test', 1);
		return true;
	}

	public function get(Request $request) {
		return icache()->channel('test')->get('test');
	}

	public function destroy(Request $request) {
		return icache()->channel('test')->delete('test');
	}
}