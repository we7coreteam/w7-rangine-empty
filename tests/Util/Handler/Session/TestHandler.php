<?php

namespace W7\App\Handler\Session;

use W7\Core\Session\Handler\HandlerAbstract;

class TestHandler extends HandlerAbstract {
	private $data;

	public function write($session_id, $session_data) {
		$this->data[$session_id] = $session_data;
	}

	public function read($session_id) {
		return $this->data[$session_id];
	}

	public function destroy($session_id, $flag = SESSION_DESTROY) {
		unset($this->data[$session_id]);
	}

	public function gc($maxlifetime) {
		//清理过期的session, 根据具体的存储方式清理
	}
}