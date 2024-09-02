<?php

namespace App\Services;

use App\Database\Connection;
use App\Database\Models\UserModel;
use App\Session\Session;
use PDO;

class LoginService
{
    private Connection $db;
    private Session $session;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->session = Session::getInstance();
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function isPasswordCorrect(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function findUser(string $login): ?UserModel
    {
        $query = <<<SQL
            SELECT * from users where login = :login
        SQL;

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchObject(UserModel::class) ?: null;
    }

    public function logIn(UserModel $user): void
    {
        $this->session->setSessionValue('authorisation', [
            'id' => $user->id,
            'login' => $user->login
        ]);
    }

    public function logOut():void
    {
        $this->session->clearSessionValue('authorisation');
    }
}