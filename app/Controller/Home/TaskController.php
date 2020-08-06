<?php

namespace W7\App\Controller\Home;

use W7\App\Task\QueueDefaultTask;
use W7\Core\Controller\ControllerAbstract;
use W7\Core\Facades\Task;

class TaskController extends ControllerAbstract {
	public function index() {
		Task::dispatch(QueueDefaultTask::class, [
			'test' => true
		]);

		return 'success';
	}
}