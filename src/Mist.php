<?php declare(strict_types=1);

namespace Frames\Mist;

class Mist
{
    private static ?Mist $instance = null;
    private Config $config;
    private array $mocks = [];

    private function __construct()
    {
        // Default configuration
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
     * Register a mock response for a specified HTTP method.
     *
     * @param string $method
     * @param Response $response
     * @return void
     */
    private static function register(string $method, Response $response): void
    {
        $instance = self::getInstance();
        $instance->mocks[$method][] = $response;
    }

    /**
     * Register a POST mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function post(Response $response): void
    {
        self::register('POST', $response);
    }

    /**
     * Register a GET mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function get(Response $response): void
    {
        self::register('GET', $response);
    }

    /**
     * Register a PUT mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function put(Response $response): void
    {
        self::register('PUT', $response);
    }

    /**
     * Register a DELETE mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function delete(Response $response): void
    {
        self::register('DELETE', $response);
    }

    /**
     * Retrieve all registered mocks.
     *
     * @return array
     */
    public function getMocks(): array
    {
        return $this->mocks;
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
