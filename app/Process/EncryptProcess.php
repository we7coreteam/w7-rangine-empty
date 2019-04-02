<?php
/**
 * @author donknap
 * @date 19-4-2 上午10:09
 */

namespace W7\App\Process;


use Swoole\Process;
use W7\Core\Process\ProcessAbstract;

class EncryptProcess extends ProcessAbstract {
	public function run(Process $process) {
		echo 'This is user process';
		return true;
	}

	public function check() {
		return true;
	}
}