<?php

namespace App\Repositories;

use App\Classes\Database;
use DateTimeImmutable;
use PDO;

class UserRepository
{
    public function __construct(protected Database $db) {}

    public function getByEmail(string $email): ?object
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        return $this->db->objectOrNull($this->db->execute($sql, ['email' => $email])->fetch(PDO::FETCH_OBJ));
    }

    public function getById(int $id): ?object
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        return $this->db->objectOrNull($this->db->execute($sql, ['id' => $id])->fetch(PDO::FETCH_OBJ));
    }

    public function createUser(string $name, string $email, string $password): int
    {
        $now = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $sql = <<<SQL
            INSERT INTO users (name, email, password, created_at, updated_at)
            VALUES (:name, :email, :password, :created_at, :updated_at)
        SQL;

        $this->db->execute($sql, [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return (int) $this->db->lastInsertId();
    }
}
