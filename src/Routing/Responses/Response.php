<?php

namespace App\Routing\Responses;

use App\Twig;

class Response implements ResponseInterface
{
    private string $view;
    private array $parameters;

    public function __construct(string $view, array $parameters)
    {
        $this->view = $view;
        $this->parameters = $parameters;
    }

    public function handle()
    {
        echo Twig::getInstance()->render($this->view, $this->parameters);
    }
}