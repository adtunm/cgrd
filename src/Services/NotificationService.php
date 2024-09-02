<?php

namespace App\Services;

use App\Session\Session;

class NotificationService
{
    private const KEY = 'notification';
    private Session $session;

    public function __construct()
    {
        $this->session = Session::getInstance();
    }

    public function addNotification(string $text, string $status): void
    {
        $this->session->setSessionValue(self::KEY, [
            'value' => $text,
            'status' => $status,
        ]);
    }

    public function getNotification(): ?array
    {
        $notification = $this->session->getSessionValue(self::KEY);
        $this->session->clearSessionValue(self::KEY);

        return $notification;
    }
}