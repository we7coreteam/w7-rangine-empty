<?php

//apiresource的用法和laravel一致
//irouter()->apiResource()->names()->middleware()->only()->except()->parameters();

irouter()->apiResource('test', 'Home\WelcomeController')->middleware('TestMiddleware');

