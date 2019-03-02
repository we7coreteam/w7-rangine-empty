<?php
/**
 * @author donknap
 * @date 19-3-2 下午1:48
 */

namespace W7\App\Controller;


use W7\Core\Controller\ControllerAbstract;
use W7\Http\Message\Server\Request;

class HomeController extends ControllerAbstract {
	public function index(Request $request) {
		return 'helloWorld';
	}

	public function userLogin(Request $request, $uid = 0) {
		return 'user-login ---- uid : ' . $uid . ' --- post: ' . $request->post('password');
	}
}