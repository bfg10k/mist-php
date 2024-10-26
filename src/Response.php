<?php declare(strict_types=1);

namespace Frames\Mist;

namespace Frames\Mist;

class Response
{
    private string $path;
    private array $headers;
    private string $body;

    private int $delay = 0;

    private bool $isDynamic = false;

    /** @var callable|null */
    private mixed $dynamicResponse;

    private function __construct(string $path)
    {
        $this->path = $path;
        $this->headers = [];
        $this->body = '';
        $this->dynamicResponse = null;
    }

    /**
     * Create a new Response instance.
     *
     * @param string $path Route path with optional parameters e.g., /api/user/{id}
     * @return self
     */
    public static function new(string $path): self
    {
        return new self($path);
    }

    /**
     * Set response headers.
     *
     * @param array $headers
     * @return self
     */
    public function headers(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Set response body.
     *
     * @param string $body
     * @return self
     */
    public function body(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Set a dynamic response callable.
     *
     * @param callable $callback Function with signature function(array $params, \Psr\Http\Message\ServerRequestInterface $request): array
     * @return self
     */
    public function dynamicResponse(callable $callback): self
    {
        $this->isDynamic = true;
        $this->dynamicResponse = $callback;
        return $this;
    }

    /**
     * @param int $milliseconds Allows to add a delay for handing the response. Time is in milliseconds.
     * @return $this
     */
    public function delay(int $milliseconds): self
    {
        $this->delay = $milliseconds;
        return $this;
    }

    /**
     * Get the route path.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get the response headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Get the response body.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Check if the content is dynamic.
     *
     * @return bool True if the content is dynamic, false otherwise.
     */
    public function isDynamic(): bool
    {
        return $this->isDynamic;
    }

    /**
     * Get the dynamic response callable.
     */
    public function getDynamicResponse(): ?callable
    {
        return $this->dynamicResponse;
    }

    /**
     * Get the delay time.
     * @return int The delay time in milliseconds.
     */
    public function getDelay(): int
    {
        return $this->delay;
    }
}