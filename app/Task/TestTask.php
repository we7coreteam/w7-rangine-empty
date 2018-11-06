<?php
/**
 * @author donknap
 * @date 18-8-15 下午6:50
 */

namespace W7\App\Task;

use W7\App\Model\Entity\Licenses;
use W7\App\Model\Entity\Util\Invoice;
use W7\Core\Task\TaskInterface;

class TestTask implements TaskInterface {

	public function run($params = []) {
		ilogger()->info('task test');
		$row = Invoice::find(3);
		ilogger()->info($row);
		print_r($row);
		return $row;
	}
}