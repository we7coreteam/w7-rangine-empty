<?php

namespace W7\App\Task;

use W7\Core\Task\TaskAbstract;

class TestTask extends TaskAbstract {
	public function run($server, $taskId, $workId, $data) {
		echo 'run';
	}
}
