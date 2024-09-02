<?php

namespace App\Routing\Responses;

use App\Routing\Router;

class RedirectResponse implements ResponseInterface
{

    private string $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function handle()
    {
        $pageInfo = Router::findByKey($this->key);
        header('Location: ' . $pageInfo->uri, true, 302);
    }
}