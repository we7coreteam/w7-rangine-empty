<?php

namespace W7\Tests\Model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use W7\Core\Database\ModelAbstract;
use W7\Core\Dispatcher\EventDispatcher;
use W7\Core\Facades\Event;
use W7\Core\Listener\ListenerAbstract;

class User extends ModelAbstract {
	protected $table = 'user';
	protected $dispatchesEvents = [
		'saved' => SavedEvent::class
	];
}

class SavedEvent {
	private $user;

	public function __construct(User $user) {
		$this->user = $user;
	}

	public function check() {
		$this->user->saved = true;
	}
}

class SavedListener extends ListenerAbstract {
	public function run(...$params) {
		/**
		 * @var SavedEvent $savedEvent
		 */
		$savedEvent = $params[0];
		$savedEvent->check();
	}
}

class EventTest extends ModelTestAbstract {
	public function testEvent() {
		/**
		 * @var EventDispatcher $event
		 */
		$event = Event::getFacadeRoot();
		$event->listen(SavedEvent::class, SavedListener::class);
		Schema::dropIfExists('user');
		Schema::create('user', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->timestamps();
			$table->string('name');
		});

		$model = new User();
		$model->id = 1;
		$model->name = 'test';
		$model->save();

		$value = (new User())->where('id', '=', 1)->first();
		$this->assertSame(1, $value->id);
		$this->assertSame('test', $value->name);
		$this->assertSame(true, $model->saved);

		Schema::dropIfExists('user');
	}
}