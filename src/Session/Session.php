<?php

namespace App\Session;

class Session
{
    private bool $sessionClosed;
    protected static Session $instance;

    protected ?array $sessionArray = [];

    private function __construct()
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            session_start();
            $this->sessionClosed = false;
        }

        $this->sessionArray = $_SESSION;
        $this->setSessionValue('started', 'yes');
    }


    public static function getInstance(): static
    {
        if (!isset(self::$instance)) {
            throw new \Exception('Not initialized');
        }

        return self::$instance;
    }

    public static function init(): void
    {
        self::$instance = new static();
    }

    public function clearSession(): void
    {
        $this->sessionArray = [];
        session_reset();
    }

    public function clearSessionValue($key): void
    {
        if ($this->sessionArray !== null && array_key_exists($key, $this->sessionArray)) {
            unset($this->sessionArray[$key]);
            $this->saveToSession();
        }
    }

    public function getSession(): ?array
    {
        return $this->sessionArray;
    }

    public function getSessionValue(string $key): null|array|string|int
    {
        if ($this->sessionArray === null || !array_key_exists($key, $this->sessionArray)) {
            return null;
        }

        return $this->sessionArray[$key];
    }

    public function setSession(array $values): void
    {
        $this->sessionArray = $values;
    }

    public function setSessionValue(string $key, array|string|int $values): void
    {
        $this->sessionArray[$key] = $values;
    }

    public function saveToSession(): void
    {
        if ($this->sessionClosed) {
            throw new \Exception('Session already closed');
        }

        $_SESSION = $this->sessionArray;
        session_write_close();
    }

}