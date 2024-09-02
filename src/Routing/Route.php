<?php

namespace App\Routing;

use App\Enums\HttpMethods;

readonly class Route
{
    public string $uri;
    public string $controller;
    public HttpMethods $method;
    public string $key;
    public string $function;
    public bool $restricted;

    /**
     * @param string $uri
     * @param string $controller
     * @param string $key
     * @param HttpMethods $method
     * @param string $function
     * @param bool $restricted
     */
    public function __construct(
        string $uri,
        string $controller,
        string $key,
        HttpMethods $method,
        string $function,
        bool $restricted
    ) {
        $this->uri = $uri;
        $this->controller = $controller;
        $this->key = $key;
        $this->method = $method;
        $this->function = $function;
        $this->restricted = $restricted;
    }
}