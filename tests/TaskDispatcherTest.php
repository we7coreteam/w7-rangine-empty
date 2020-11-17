<?php

namespace W7\Tests;

use W7\App;
use W7\App\Task\TestTask;
use W7\Crontab\Task\CronTask;
use W7\Facade\Task;

class TaskDispatcherTest extends TestCase {
	public function testTaskCheck() {
		$task = new CronTask('test', [
			'rule' => '*/2 * * * *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:0');
		$this->assertSame(true, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:13:1');
		$this->assertSame(false, $task->getTrigger()->trigger($time));

		$task = new CronTask('test', [
			'rule' => '*/2 */2 * * * *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:13:12');
		$this->assertSame(false, $task->getTrigger()->trigger($time));

		$task = new CronTask('test', [
			'rule' => '*/2 */2 12 * * *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:11:13');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:11:12');
		$this->assertSame(false, $task->getTrigger()->trigger($time));

		$task = new CronTask('test', [
			'rule' => '*/2 */2 12 10 10 *',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-11 12:12:13');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:11:13');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:11:12');
		$this->assertSame(false, $task->getTrigger()->trigger($time));

		$task = new CronTask('test', [
			'rule' => '*/2 */2 12 10 10 4',
			'task' => ''
		]);
		$time = strtotime('2019-10-10 12:12:12');
		$this->assertSame(true, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-11 12:12:13');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:11:13');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
		$time = strtotime('2019-10-10 12:11:12');
		$this->assertSame(false, $task->getTrigger()->trigger($time));
	}

	public function testDispatcher() {
		copy(__DIR__ . '/Util/Task/TestTask.php', APP_PATH . '/Task/TestTask.php');

		ob_start();
		App::$server = new \W7\Fpm\Server\Server();
		Task::dispatch(TestTask::class);
		$echo = ob_get_clean();
		$this->assertSame('run', $echo);

		unlink(APP_PATH . '/Task/TestTask.php');
	}
}