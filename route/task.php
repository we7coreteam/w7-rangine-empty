<?php

use W7\Core\Facades\Router;

Router::middleware('TestMiddleware')->get('/task', 'Home\TaskController@index');