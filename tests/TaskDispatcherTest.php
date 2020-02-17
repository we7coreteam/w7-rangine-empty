<?php

namespace W7\Tests;

use W7\App\Task\TestTask;
use W7\Core\Dispatcher\TaskDispatcher;
use W7\Crontab\Server\Server;
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
		$time = strtotime('2019-10-10 12:13:12');
		$this->assertSame(false, $task->check($time));

		$task = new Task('test', [
			'rule' => '*/2 */2 12 * * *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->check($time));
		$time = strtotime('2019-10-10 12:11:13');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:11:12');
		$this->assertSame(false, $task->check($time));

		$task = new Task('test', [
			'rule' => '*/2 */2 12 10 10 *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->check($time));
		$time = strtotime('2019-10-11 12:12:13');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:11:13');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:11:12');
		$this->assertSame(false, $task->check($time));

		$task = new Task('test', [
			'rule' => '*/2 */2 12 10 10 4',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->check($time));
		$time = strtotime('2019-10-11 12:12:13');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:11:13');
		$this->assertSame(false, $task->check($time));
		$time = strtotime('2019-10-10 12:11:12');
		$this->assertSame(false, $task->check($time));
	}

	public function testDispatcher() {
		copy(__DIR__ . '/Util/Task/TestTask.php', APP_PATH . '/Task/TestTask.php');

		$dispatcher = new TaskDispatcher();
		$task = new Task('test', [
			'rule' => '*/2 * * * *',
			'class' => TestTask::class
		]);

		ob_start();
		$dispatcher->dispatch(new Server(), 0, 0, $task->getTaskMessage()->pack());
		$echo = ob_get_clean();
		$this->assertSame('run', $echo);

		unlink(APP_PATH . '/Task/TestTask.php');
	}
}