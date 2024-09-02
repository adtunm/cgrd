<?php

namespace App\Routing\Responses;

class JsonResponse  implements ResponseInterface
{

    private array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function handle()
    {
        echo json_encode($this->response);
    }
}