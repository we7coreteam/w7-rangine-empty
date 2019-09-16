<?php
/**
 * @author donknap
 * @date 18-11-12 下午4:21
 */

namespace W7\App\Controller\Home;


use W7\Core\Controller\ControllerAbstract;

class WelcomeController extends ControllerAbstract {

	/**
	 * 访问URL http://127.0.0.1:88/
	 */
	public function index() {
		return 'Hello World';
	}
}