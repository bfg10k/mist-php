<?php declare(strict_types=1);

namespace Frames\Mist;

use Frames\Mist\Config\Config;
use Frames\Mist\Http\Endpoint;

class Mist
{
    private static ?Mist $instance = null;
    private Config $config;
    private array $endpoints = [];

    private function __construct()
    {
        $this->config = new Config();
    }

    /**
     * Retrieve the singleton instance of Mist.
     */
    public static function getInstance(): Mist
    {
        if (self::$instance === null) {
            self::$instance = new Mist();
        }
        return self::$instance;
    }

    /**
     * Configure the server settings.
     *
     * @param Config $config
     * @return void
     */
    public static function configure(Config $config): void
    {
        self::getInstance()->config = $config;
    }

    /**
     * Register a POST endpoint.
     *
     * @param string $path
     * @return Endpoint
     */
    public static function post(string $path): Endpoint
    {
        return self::register('POST', $path);
    }

    /**
     * Register a GET endpoint.
     *
     * @param string $path
     * @return Endpoint
     */
    public static function get(string $path): Endpoint
    {
        return self::register('GET', $path);
    }

    /**
     * Register a PUT endpoint.
     *
     * @param string $path
     * @return Endpoint
     */
    public static function put(string $path): Endpoint
    {
        return self::register('PUT', $path);
    }

    /**
     * Register a DELETE endpoint.
     *
     * @param string $path
     * @return Endpoint
     */
    public static function delete(string $path): Endpoint
    {
        return self::register('DELETE', $path);
    }

    /**
     * Register an endpoint for a specified HTTP method.
     *
     * @param string $method
     * @param string $path
     * @return Endpoint
     */
    private static function register(string $method, string $path): Endpoint
    {
        $instance = self::getInstance();
        $endpoint = new Endpoint($method, $path);
        $instance->endpoints[] = $endpoint;
        return $endpoint;
    }

    /**
     * Retrieve all registered endpoints.
     *
     * @return Endpoint[]
     */
    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    /**
     * Retrieve the server configuration.
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }
}
