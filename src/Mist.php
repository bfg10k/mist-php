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
     * Register a POST mock response.
     *
     * @param string $path
     * @return Response
     */
    public static function post(string $path): Response
    {
        return self::register('POST', $path);
    }

    /**
     * Register a GET mock response.
     *
     * @param string $path
     * @return Response
     */
    public static function get(string $path): Response
    {
        return self::register('GET', $path);
    }

    /**
     * Register a PUT mock response.
     *
     * @param string $path
     * @return Response
     */
    public static function put(string $path): Response
    {
        return self::register('PUT', $path);
    }

    /**
     * Register a DELETE mock response.
     *
     * @param string $path
     * @return Response
     */
    public static function delete(string $path): Response
    {
        return self::register('DELETE', $path);
    }

    /**
     * Register a mock response for a specified HTTP method.
     *
     * @param string $method
     * @param string $path
     * @return Response
     */
    private static function register(string $method, string $path): Response
    {
        $instance = self::getInstance();
        $response = Response::new($path);
        $instance->mocks[$method][] = $response;
        return $response;
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
