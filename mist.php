<?php

require 'vendor/autoload.php';

use Frames\Mist\Mist;
use Frames\Mist\Response;
use Frames\Mist\Config;

Mist::configure(new Config('127.0.0.1', 8080, false, null, null));

Mist::post(
    Response::new('/api/message/{id}')
        ->headers(['Content-Type' => 'application/json'])
        ->body(json_encode(['message' => 'Hello, World!']))
);

Mist::get(
    Response::new('/api/user/{id}')
        ->headers(['Content-Type' => 'application/json'])
        ->dynamicResponse(function ($params, $request) {
            $id = $params['id'];
            return ['id' => $id, 'name' => 'User ' . $id];
        })
    ->delay(10000)
);