<?php declare(strict_types=1);

namespace Frames\Mist\Server;

use Frames\Mist\Config\Config;
use Frames\Mist\Mist;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Http\Message\Response as ReactResponse;
use React\Socket\SocketServer;

class Server
{
    /**
     * Run the Mist server with the configured endpoints.
     *
     * @return void
     */
    public static function run(): void
    {
        $mist = Mist::getInstance();
        $config = $mist->getConfig();
        $endpoints = $mist->getEndpoints();

        $server = new HttpServer(function (ServerRequestInterface $request) use ($endpoints) {
            foreach ($endpoints as $endpoint) {
                $params = $endpoint->match($request);
                if ($params !== null) {
                    return $endpoint->handle($request, $params);
                }
            }
            return self::notFoundResponse();
        });

        self::startSocketServer($server, $config);
    }

    private static function startSocketServer(HttpServer $server, Config $config): void
    {
        $host = $config->host;
        $port = $config->port;
        $socketAddress = "{$host}:{$port}";

        $context = $config->https ? [
            'tls' => [
                'local_cert' => $config->certPath,
                'local_pk' => $config->keyPath,
            ]
        ] : [];

        $socket = new SocketServer($socketAddress, $context);
        $server->listen($socket);

        $protocol = $config->https ? "https" : "http";
        echo "Mist Server running at {$protocol}://{$host}:{$port}\n";
    }

    private static function notFoundResponse(): ReactResponse
    {
        return new ReactResponse(
            404,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'Not Found'])
        );
    }
}
