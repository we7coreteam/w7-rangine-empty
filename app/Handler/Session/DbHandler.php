<?php

namespace W7\App\Handler\Session;

use W7\App\Model\Entity\Session;
use W7\App\Model\Entity\User\Online;
use W7\Core\Session\Handler\HandlerAbstract;

class DbHandler extends HandlerAbstract {
	public function write($session_id, $session_data) {
		$session = Session::query()->where('session_id', '=', $session_id)->first();
		if (empty($session)) {
			Session::query()->create([
				'session_id' => $session_id,
				'data' => $session_data,
				'expired_at' => time() + $this->getExpires(),
			]);
		} else {
			$session->data = $session_data;
			$session->expired_at = time() + $this->getExpires();
			$session->save();
		}

		$userInfo = $this->unpack($session_data);
		if (!empty($userInfo['user'])) {
			//存储session的时候，再存一份在线用户列表，也可以写入 redis
			if (!(Online::query()->where('user_id', '=', $userInfo['user']['uid'])->exists())) {
				Online::query()->create([
					'user_id' => $userInfo['user']['uid'],
					'user_name' => $userInfo['user']['username'],
					'fd' => $userInfo['fd'],
				]);
			}
		}

		return true;
	}

	public function read($session_id) {
		if (empty($session_id)) {
			return '';
		}
		$session = Session::query()->where('session_id', '=', $session_id)->first();
		if (empty($session) || $session->expired_at < time()) {
			return '';
		}
		return $session->data;
	}

	public function destroy($session_id) {
		Session::query()->where('session_id', '=', $session_id)->delete();
		return true;
	}

	public function close($session_id = '') {
		if (php_sapi_name() == 'cli' && $session_id) {
			$userInfo = $this->unpack($this->read($session_id));
			if (!empty($userInfo['user'])) {
				Online::query()->where([
					'user_id' => $userInfo['user']['uid']
				])->delete();
			}
		}

		return true;
	}

	public function gc($maxlifetime) {
		return true;
	}
}