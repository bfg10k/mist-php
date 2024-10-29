<?php

require 'vendor/autoload.php';

use Frames\Mist\Config\Config;
use Frames\Mist\Mist;

Mist::configure(new Config('127.0.0.1', 8080, false, null, null));

Mist::post('/api/message/{id}')
    ->headers(['Content-Type' => 'application/json'])
    ->body(json_encode(['message' => 'Hello, World!']));

Mist::get('/api/user/{id}')
    ->headers(['Content-Type' => 'application/json'])
    ->dynamicResponse(function ($params, $request) {
        $id = $params['id'];
        return ['id' => $id, 'name' => 'User ' . $id];
    })
    ->delay(10000);