<?php

irouter()->post('/user/login', [\W7\App\Controller\User\AuthController::class, 'login']);
irouter()->post('/user/message/broadcast', [\W7\App\Controller\User\MessageController::class, 'broadcast']);
irouter()->post('/user/message/send-to', [\W7\App\Controller\User\MessageController::class, 'sendTo']);