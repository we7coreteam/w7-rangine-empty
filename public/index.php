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

require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

$server = new \W7\Fpm\Server\Server();
new \W7\App();
$server->start();
