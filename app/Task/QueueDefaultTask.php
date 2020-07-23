<?php

namespace W7\App\Task;

use W7\Mq\Task\QueueTaskAbstract;

class QueueDefaultTask extends QueueTaskAbstract {
	public function run($server, $taskId, $workId, $data) {
		echo serialize($data);
	}
}