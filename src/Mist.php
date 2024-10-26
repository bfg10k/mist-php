<?php declare(strict_types=1);

namespace Frames\Mist;

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
     * @param Response $response
     * @return void
     */
    public static function addPost(Response $response): void
    {
        self::getInstance()->mocks['POST'][] = $response;
    }

    /**
     * Register a GET mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function addGet(Response $response): void
    {
        self::getInstance()->mocks['GET'][] = $response;
    }

    /**
     * Register a PUT mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function addPut(Response $response): void
    {
        self::getInstance()->mocks['PUT'][] = $response;
    }

    /**
     * Register a DELETE mock response.
     *
     * @param Response $response
     * @return void
     */
    public static function addDelete(Response $response): void
    {
        self::getInstance()->mocks['DELETE'][] = $response;
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