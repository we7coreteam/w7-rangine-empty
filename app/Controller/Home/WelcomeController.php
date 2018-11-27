<?php
/**
 * @author donknap
 * @date 18-11-12 下午4:21
 */

namespace W7\App\Controller\Home;


use W7\Core\Controller\ControllerAbstract;

class WelcomeController extends ControllerAbstract {

	/**
	 * 访问URL http://127.0.0.1:88/home/welcome/index
	 */
	public function index() {
		return 'Hello World';
	}

	public function index1() {
		return $this->responseHtml('<h1>Hello World</h1>');
	}

	public function index2() {
		return $this->responseRaw('<h1>Hello World</h1>');
	}
}