<?php

namespace W7\App\Task;

use W7\Core\Task\TaskAbstract;

class QueueDefaultTask extends TaskAbstract {
	public function run($server, $taskId, $workId, $data) {
		var_dump($data);
	}

	public function failed($e) {
		var_dump($e->getMessage());
	}

	public static function shouldQueue() {
		return true;
	}
}