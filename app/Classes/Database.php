<?php

declare(strict_types=1);

namespace App\Classes;

use PDO;
use PDOStatement;

class Database extends PDO
{
    /**
     * @param array<array-key,mixed> $options
     */
    public function __construct(string $dsn, ?string $username = null, string $password = null, array $options = [])
    {
        parent::__construct($dsn, $username, $password, $options);
    }

    /**
     * @param array<array-key,mixed> $context
     */
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
