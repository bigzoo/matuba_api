<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->resource('users', 'App\Http\Controllers\Api\UserController', ['except' => ['index']]);
    $api->resource('reports', 'App\Http\Controllers\Api\ReportsController');
    $api->post('journeys', 'App\Http\Controllers\JourneysController@show');
    $api->get('journeys', 'App\Http\Controllers\JourneysController@showGet');
});
