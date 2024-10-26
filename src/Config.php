<?php declare(strict_types=1);

namespace Frames\Mist;

class Config
{
    private string $host;
    private int $port;
    private bool $https;
    private ?string $certPath;
    private ?string $keyPath;
    private array $directories;

    public function __construct(
        string  $host = 'localhost',
        int     $port = 8080,
        bool    $https = false,
        ?string $certPath = null,
        ?string $keyPath = null,
        array   $directories = ['mocks']
    )
    {
        $this->host = $host;
        $this->port = $port;
        $this->https = $https;
        $this->certPath = $certPath;
        $this->keyPath = $keyPath;
        $this->directories = $directories;
    }

    // Getters and setters

    public function getHost(): string
    {
        return $this->host;
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function isHttps(): bool
    {
        return $this->https;
    }

    public function setHttps(bool $https): void
    {
        $this->https = $https;
    }

    public function getCertPath(): ?string
    {
        return $this->certPath;
    }

    public function setCertPath(?string $certPath): void
    {
        $this->certPath = $certPath;
    }

    public function getKeyPath(): ?string
    {
        return $this->keyPath;
    }

    public function setKeyPath(?string $keyPath): void
    {
        $this->keyPath = $keyPath;
    }

    public function getDirectories(): array
    {
        return $this->directories;
    }

    public function setDirectories(array $directories): void
    {
        $this->directories = $directories;
    }
}