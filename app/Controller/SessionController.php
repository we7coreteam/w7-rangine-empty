<?php


namespace W7\App\Controller;


use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class SessionController extends ControllerAbstract {
	public function set(Request $request) {
		$request->session->set('test', 1);

		return true;
	}

	public function get(Request $request) {
		return $request->session->get('test');
	}

	public function destroy(Request $request) {
		return $request->session->destroy();
	}
}