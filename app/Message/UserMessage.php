<?php
/**
 * @author donknap
 * @date 19-4-9 下午10:22
 */

namespace W7\App\Message;


use W7\Core\Message\MessageAbstract;
use W7\Core\Message\MessageTraiter;

class UserMessage extends MessageAbstract {
	use MessageTraiter;

	public $messageType = 'user';

	public $uid;

	public $username;

	public function isBoy() {
		return true;
	}
}