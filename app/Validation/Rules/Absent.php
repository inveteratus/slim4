<?php

namespace App\Validation\Rules;

use App\Classes\Database;
use PDO;
use Respect\Validation\Rules\AbstractRule;

class Absent extends AbstractRule
{
    public function __construct(protected Database $db, protected string $table, protected string $field) {}

    public function validate($input): bool
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}` WHERE `{$this->field}` = :value";

        return (int) $this->db->execute($sql, ['value' => $input])->fetch(PDO::FETCH_COLUMN) == 0;
    }
}
