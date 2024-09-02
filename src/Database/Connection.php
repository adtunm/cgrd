<?php

namespace App\Database;

use PDO;

class Connection
{
    private PDO $connection;
    public static Connection $instance;
    private function __construct()
    {
        $this->connection = new PDO("mysql:host=". $_ENV["DB_HOST"] . ";dbname=" . $_ENV["DB_DATABASE"], $_ENV["DB_USERNAME"], $_ENV["DB_PASSWORD"]);
    }

    public static function init(): void
    {
        self::$instance = new static();
    }

    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            throw new \Exception('Not initialized');
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

}