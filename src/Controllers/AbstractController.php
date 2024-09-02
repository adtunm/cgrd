<?php

namespace App\Controllers;

use App\Services\NotificationService;
use App\Session\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AbstractController
{
    protected Session $session;
    protected NotificationService $notification;

    public const RESTRICTED_METHODS = [];

    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->notification = new NotificationService();
    }

    public function isLoggedIn(): bool
    {
        return !empty($this->session->getSessionValue('authorisation'));
    }

    public function checkParameters(object &$request, array $parameters): void
    {
        foreach ($parameters as $parameter => $type) {
            if (!property_exists($request, $parameter)) {
                throw new \Exception(sprintf('Required parameter: %s dont exist.', $parameter));
            }
            switch ($type) {
                case 'int':
                    if (!is_int($request->$parameter)) {
                        $request->$parameter = (int)$request->$parameter;
                    }
                    break;
                case 'float':
                    if (!is_float($request->$parameter)) {
                        $request->$parameter = (float)$request->$parameter;
                    }
                    break;
                case 'string':
                    if (!is_string($request->$parameter)) {
                        $request->$parameter = (string)$request->$parameter;
                    }
                    break;
            }
        }
    }

}