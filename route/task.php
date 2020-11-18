<?php

use W7\Facade\Router;

Router::middleware('TestMiddleware')->get('/task', 'Home\TaskController@index');