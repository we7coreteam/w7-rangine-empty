<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-7-23
 * Time: 下午12:01
 */

namespace W7\App\Controller;



use W7\App\Model\Entity\Licenses;
use W7\App\Task\TestTask;
use w7\Http\Message\Base\Request;

class TestController {
	public function index(Request $request, $id) {

		return 'Hello World';
	}

	public function task() {
		itask(TestTask::class, ['renchao' => 'chao', 'asdf' => 32132]);
		return 3;
	}
}