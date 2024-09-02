<?php

namespace App\Controllers;

use App\Routing\Responses\RedirectResponse;
use App\Routing\Responses\Response;
use App\Routing\Responses\ResponseInterface;
use App\Services\LoginService;

class Login extends AbstractController
{
    public const RESTRICTED_METHODS = ['logout'];
    private LoginService $loginService;
    public function __construct()
    {
        parent::__construct();
        $this->loginService = new LoginService();
    }

    public function index(): ResponseInterface
    {
        if ($this->isLoggedIn()) {
            return new RedirectResponse('news');
        }

        $notification = $this->notification->getNotification();

        return new Response('login.html.twig', [
            'notification' => $notification['value'] ?? null,
            'notificationStatus' => $notification['status'] ?? null,
            'pageTitle' => 'Login'
        ]);
    }
    public function login(\stdClass $data): ResponseInterface
    {
        $this->checkParameters($data, [
            'username' => 'string',
            'password' => 'string'
        ]);

        if ($this->isLoggedIn()) {
            return new RedirectResponse('news');
        }

        $user = $this->loginService->findUser($data->username);

        if ($user === null) {
            $this->notification->addNotification('Wrong login or password.', 'ERROR');

            return new RedirectResponse('index');
        }

        $isPasswordCorrect = $this->loginService->isPasswordCorrect($data->password, $user->password);

        if ($isPasswordCorrect) {
            $this->notification->addNotification('Successful log in.', 'OK');
            $this->loginService->logIn($user);

            return new RedirectResponse('news');
        } else {
            $this->notification->addNotification('Wrong login or password.', 'ERROR');

            return new RedirectResponse('index');
        }
    }

    public function logout(): ResponseInterface
    {
        $this->loginService->logOut();
        $this->notification->addNotification('You are logged out.', 'OK');

        return new RedirectResponse('index');
    }
}