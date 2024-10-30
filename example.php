<?php

require 'vendor/autoload.php';

use Frames\Mist\Config\Config;
use Frames\Mist\Http\Response;
use Frames\Mist\Mist;

Mist::configure(new Config('127.0.0.1', 8080, false, null, null));

Mist::post('/api/message/{id}')
    ->withHeaders(['Content-Type' => 'application/json'])
    ->staticResponse('{"id": "{$id}"}');

// Define a dynamic response endpoint
Mist::get('/api/user/{id}')
    ->withHeaders(['Content-Type' => 'application/json'])
    ->dynamicResponse(function ($params, $request) {
        if ($params['id'] == '123') {
            return Response::new(200, ['status' => 'found', 'user' => $params]);
        } elseif ($params['id'] == '456') {
            return Response::new(200, 'User data in plain text format');
        } else {
            $response = Response::new(404, 'User not found');
            $response->addHeader('X-Custom-Header', 'Value');
            return $response;
        }
    })
    ->delay(1000);

// Define a dynamic response endpoint
Mist::get('/api/users')
    ->withHeaders(['Content-Type' => 'application/json'])
    ->staticResponse('[{id:1, id:2}]');