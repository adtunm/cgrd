<?php

namespace App\Routing;

use App\Enums\HttpMethods;

class Request
{
    private static Request $instance;
    private string $uri;
    private HttpMethods $method;
    private array $query;
    private array $request;

    public static function init(): void
    {
        $uri = $_SERVER["REDIRECT_URL"] ?? '/';
        $method = HttpMethods::getFromValue($_SERVER["REQUEST_METHOD"]) ;
        $query = $_GET;
        $request = $_POST;

        self::$instance = new static($uri, $method, $query, $request);
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    private function __construct(string $uri, HttpMethods $method, array $query, array $request)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->query = $query;
        $this->request = $request;
    }

    public static function getInstance(): static
    {
        if (!isset(self::$instance)) {
            throw new \Exception('Not initialized');
        }

        return self::$instance;
    }
}