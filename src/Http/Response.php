<?php declare(strict_types=1);

namespace Frames\Mist\Http;

use JsonSerializable;
use React\Http\Message\Response as ReactResponse;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';
    private array $cookies = [];

    private function __construct() {}

    public static function new(int $statusCode = 200, JsonSerializable|array|string $body = [], array $headers = []): self
    {
        return (new self())
            ->withStatusCode($statusCode)
            ->withBody($body)
            ->withHeaders($headers);
    }

    public function withStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function addHeader(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function addCookie(string $name, string $value, array $options = []): self
    {
        $this->cookies[$name] = ['value' => $value, 'options' => $options];
        return $this;
    }

    public function withBody(JsonSerializable|array|string $body): self
    {
        $this->body = (is_array($body) || $body instanceof JsonSerializable) ? json_encode($body) : $body;
        return $this;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function toReactResponse(): ReactResponse
    {
        // Add cookies to headers
        if (!empty($this->cookies)) {
            $cookieStrings = [];
            foreach ($this->cookies as $name => $cookie) {
                $cookieString = urlencode($name) . '=' . urlencode($cookie['value']);
                foreach ($cookie['options'] as $key => $value) {
                    $cookieString .= "; $key=$value";
                }
                $cookieStrings[] = $cookieString;
            }
            $this->headers['Set-Cookie'] = $cookieStrings;
        }

        return new ReactResponse(
            $this->statusCode,
            $this->headers,
            $this->body
        );
    }
}
