<?php

use W7\App\Middleware\TestMiddleware;
use W7\Facade\Router;

Router::middleware(TestMiddleware::class)->get('/task', 'Home\TaskController@index');