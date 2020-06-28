<?php

use W7\Core\Facades\Router;

Router::post('/user/login', [\W7\App\Controller\User\AuthController::class, 'login']);
Router::post('/user/message/broadcast', [\W7\App\Controller\User\MessageController::class, 'broadcast']);
Router::post('/user/message/send-to', [\W7\App\Controller\User\MessageController::class, 'sendTo']);