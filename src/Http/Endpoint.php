<?php declare(strict_types=1);

namespace Frames\Mist\Http;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Loop;
use React\Promise\Promise;

class Endpoint
{
    private string $method;
    private string $pathPattern;
    private array $conditions = [];
    private array $headers = [];
    private ?Response $staticResponse = null;
    private $dynamicResponse = null;
    private int $delay = 0;

    public function __construct(string $method, string $pathPattern)
    {
        $this->method = strtoupper($method);
        $this->pathPattern = $pathPattern;
    }

    /**
     * Add headers to the response.
     *
     * @param array $headers
     * @return self
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Set a delay in milliseconds for the response.
     *
     * @param int $milliseconds
     * @return self
     */
    public function delay(int $milliseconds): self
    {
        $this->delay = $milliseconds;
        return $this;
    }

    /**
     * Define a static response with optional placeholders.
     *
     * @param string $template
     * @return self
     */
    public function staticResponse(string $template): self
    {
        $this->staticResponse = Response::new();
        $this->staticResponse->setBody($template);
        return $this;
    }

    /**
     * Define a dynamic response using a callable.
     *
     * @param callable $callable
     * @return self
     */
    public function dynamicResponse(callable $callable): self
    {
        $this->dynamicResponse = $callable;
        return $this;
    }

    /**
     * Get the response delay.
     *
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * Match the incoming request to this endpoint.
     *
     * @param ServerRequestInterface $request
     * @return array|null
     */
    public function match(ServerRequestInterface $request): ?array
    {
        if (strtoupper($request->getMethod()) !== $this->method) {
            return null;
        }

        $path = $request->getUri()->getPath();
        $pattern = $this->convertPathToRegex($this->pathPattern);

        if (preg_match($pattern, $path, $matches)) {
            $params = $this->extractPathParams($matches);

            // Additional condition checks can be added here

            return $params;
        }

        return null;
    }

    /**
     * Handle the matched request and produce a response.
     *
     * @param ServerRequestInterface $request
     * @param array $params
     * @return Promise
     */
    public function handle(ServerRequestInterface $request, array $params)
    {
        return new Promise(function ($resolve) use ($request, $params) {
            $action = function () use ($request, $params, $resolve) {
                $response = null;

                if ($this->staticResponse !== null) {
                    $body = $this->replacePlaceholders($this->staticResponse->getBody(), $params, $request);
                    $response = $this->staticResponse->withBody($body);
                } elseif ($this->dynamicResponse !== null) {
                    $result = call_user_func($this->dynamicResponse, $params, $request);

                    if ($result instanceof Response) {
                        $response = $result;
                    } else {
                        $response = Response::new();
                        $response->setBody($this->serializeResponseBody($result));
                    }
                } else {
                    $response = Response::new();
                }

                foreach ($this->headers as $key => $value) {
                    $response->addHeader($key, $value);
                }

                $resolve($response->toReactResponse());
            };

            if ($this->delay > 0) {
                Loop::get()->addTimer($this->delay / 1000, $action);
            } else {
                $action();
            }
        });
    }

    private function convertPathToRegex(string $path): string
    {
        $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return "#^{$regex}$#";
    }

    private function extractPathParams(array $matches): array
    {
        return array_filter($matches, fn($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
    }

    private function replacePlaceholders(string $template, array $params, ServerRequestInterface $request): string
    {
        foreach ($params as $key => $value) {
            $template = str_replace('{$' . $key . '}', $value, $template);
        }
        return $template;
    }

    private function serializeResponseBody($data): string
    {
        if (is_array($data) || is_object($data)) {
            return json_encode($data);
        }
        return (string) $data;
    }
}
