<?php

namespace App\Classes;

use PDO;
use PDOStatement;

class Database extends PDO
{
    public function __construct(string $dsn, ?string $username = null, string $password = null, array $options = [])
    {
        parent::__construct($dsn, $username, $password, $options);
    }

    public function execute(string $query, array $context = []): PDOStatement
    {
        $statement = $this->prepare($query);
        $statement->execute($context);

        return $statement;
    }

    public function objectOrNull(object|false $input): ?object
    {
        return is_object($input) ? $input : null;
    }
}
