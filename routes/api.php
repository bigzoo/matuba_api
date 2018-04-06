<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->resource('user','App\Http\Controllers\Api\UserController', ['except' => ['index']]);
});

