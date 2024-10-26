<?php declare(strict_types=1);

namespace Frames\Mist;

use React\Http\HttpServer;
use React\Promise\Promise;
use React\Socket\SocketServer;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response as ReactResponse;
use React\EventLoop\Loop;

/**
 * The Server class is the main entry point for starting the Mist server.
 * It uses ReactPHP to provide a non-blocking, asynchronous HTTP server
 * that allows API mocking for development and testing purposes.
 */
class Server
{
    /**
     * Run the Mist server with given configuration and mock responses.
     *
     * @param Config $config Configuration object with host, port, and SSL settings.
     * @param Response[] $mocks An array of Response objects, each defining
     *                          a mock response for an API route.
     *
     * @return void
     */
    public static function run(Config $config, array $mocks): void
    {
        $server = new HttpServer(function (ServerRequestInterface $request) use ($mocks) {
            $method = strtoupper($request->getMethod());
            $path = $request->getUri()->getPath();
            if (!isset($mocks[$method])) {
                return self::notFoundResponse();
            }

            foreach ($mocks[$method] as $mock) {
                $pattern = self::convertPathToRegex($mock->getPath());
                if (preg_match($pattern, $path, $matches)) {
                    $params = self::extractPathParams($matches);

                    return new Promise(function ($resolve) use ($mock, $params, $request) {
                        Loop::get()->addTimer($mock->getDelay() / 1000, function () use ($mock, $params, $request, $resolve) {
                            $resolve(match ($mock->isDynamic()) {
                                true => new ReactResponse(
                                    200,
                                    $mock->getHeaders(),
                                    json_encode(call_user_func($mock->getDynamicResponse(), $params, $request))
                                ),
                                false => new ReactResponse(
                                    200,
                                    $mock->getHeaders(),
                                    $mock->getBody()
                                ),
                            });
                        });
                    });
                }
            }

            return self::notFoundResponse();
        });

        self::startSocketServer($server, $config);
    }

    private static function startSocketServer(HttpServer $server, Config $config): void
    {
        $host = $config->getHost();
        $port = $config->getPort();
        $socketAddress = "{$host}:{$port}";

        $context = $config->isHttps() ? [
            'tls' => [
                'local_cert' => $config->getCertPath(),
                'local_pk' => $config->getKeyPath(),
            ]
        ] : [];

        $socket = new SocketServer($socketAddress, $context);
        $server->listen($socket);

        $protocol = $config->isHttps() ? "https" : "http";
        echo "Mist Server running at {$protocol}://{$host}:{$port}\n";
    }

    private static function convertPathToRegex(string $path): string
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return "#^{$regex}$#";
    }

    private static function extractPathParams(array $matches): array
    {
        return array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
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
