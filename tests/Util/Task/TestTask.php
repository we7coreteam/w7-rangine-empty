<?php

namespace W7\App\Task;

use W7\Core\Task\TaskAbstract;

class TestTask extends TaskAbstract {
	public static function shouldQueue() {
		return false;
	}

	public static function isAsyncTask() {
		return false;
	}

	public function run($server, $taskId, $workId, $data) {
		echo 'run';
	}
}
