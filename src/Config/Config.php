<?php declare(strict_types=1);

namespace Frames\Mist\Config;

readonly class Config
{
    public function __construct(
        public string  $host = 'localhost',
        public int     $port = 8080,
        public bool    $https = false,
        public ?string $certPath = null,
        public ?string $keyPath = null,
        public array   $directories = ['mocks']
    ) {}
}
