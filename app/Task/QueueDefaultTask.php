<?php

/**
 * WeEngine Api System
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

namespace W7\App\Task;

use W7\Core\Task\TaskAbstract;

class QueueDefaultTask extends TaskAbstract {
	public function run($server, $taskId, $workId, $data) {
		var_dump($data);
	}

	public function failed($e) {
		var_dump($e->getMessage());
	}

	public static function isAsyncTask() {
		return false;
	}

	public static function shouldQueue() {
		return false;
	}
}
