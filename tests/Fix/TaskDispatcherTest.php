<?php

namespace W7\Tests;

use W7\Crontab\Task\Task;

class TaskDispatcherTest extends TestCase {
	public function testTaskCheck() {
		$task = new Task('test', [
			'rule' => '*/2 * * * *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:1');
		$this->assertSame(true, $task->check($time));
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:13:1');
		$this->assertSame(false, $task->check($time));

		$task = new Task('test', [
			'rule' => '*/2 */2 * * * *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->check($time));
		$time = strtotime('2019-10-10 12:12:13');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:13:12');
		$this->assertSame(false, $task->check($time));
	}
}