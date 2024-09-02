<?php

namespace App\Routing;

use App\Controllers\AbstractController;
use App\Enums\HttpMethods;
use App\Session\Session;

class Router
{
    /** @var Route[] $registeredPages */
    private static array $registeredPages = [];

    /**
     * @throws \Exception
     */
    public static function register(
        string      $uri,
        string      $key,
        HttpMethods $method,
        string      $controllerName,
        string      $function,
        bool        $restricted = true
    ): void
    {
        if (array_key_exists($key, self::$registeredPages)) {
            throw new \Exception(sprintf('Key %s already exists.', $key));
        }

        if (!class_exists($controllerName)) {
            throw new \Exception(sprintf("Controller: %s don\'t exist.", $controllerName));
        }

        if (!(get_parent_class($controllerName) === AbstractController::class)) {
            throw new \Exception(sprintf("Controller: %s have to extends AbstractController", $controllerName));
        }

        if (!method_exists($controllerName, $function)) {
            throw new \Exception(sprintf("Controller: %s cant access function: %s", $controllerName, $function));
        }

        self::$registeredPages[$key] = new Route($uri, $controllerName, $key, $method, $function, $restricted);
    }

    public static function findPageInformation(Request $request): ?Route
    {
        foreach (self::$registeredPages as $page) {
            if (
                $page->uri === $request->getUri()
                && $page->method === $request->getMethod()
            ) {
                return $page;
            }
        }

        return null;
    }

    public static function findByKey(string $key): Route
    {
        foreach (self::$registeredPages as $page) {
            if ($page->key === $key) {
                return $page;
            }
        }

       throw new \Exception(sprintf('Route key: %s don\'t found.', $key));
    }

    public static function handleRequest(): void
    {
        $request = Request::getInstance();
        $pageInformation = self::findPageInformation($request);
        list($code, $message) = self::checkForError($pageInformation);

        if ($code !== null) {
            $response = throw new \Exception($message, $code);
        } else {
            $controller = new $pageInformation->controller;
            $function = $pageInformation->function;
            $response = $controller->$function((object)[...$request->getRequest(), ...$request->getQuery()]);
        }

        Session::getInstance()->saveToSession();
        $response->handle();
    }

    private static function checkForError(?Route $pageInformation): ?array
    {
        if (is_null($pageInformation)) {
           return [404, 'Page Not Found'];
        }

        $controller = $pageInformation->controller;
        if (
            defined("$controller::RESTRICTED_METHODS")
            && in_array($pageInformation->function, constant("$controller::RESTRICTED_METHODS"))
        ) {
            $auth = Session::getInstance()->getSessionValue('authorisation');

            if (empty($auth)) {
                return [403, 'Not authorized'];
            }
        }

         return null;
    }
}