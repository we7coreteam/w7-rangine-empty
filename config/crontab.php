<?php
/**
 * This file is part of Rangine
 *
 * (c) We7Team 2019 <https://www.rangine.com/>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com/ for more details
 */

return [
	'setting' => [
		'worker_num' => ienv('SERVER_CRONTAB_WORKER_NUM', 1),
		'host'  => '0.0.0.0',
		'port' => ienv('SERVER_CRONTAB_PORT', 88)
	],
	'task' => [

	]
];
