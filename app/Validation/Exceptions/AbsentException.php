<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class AbsentException extends ValidationException
{
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must not exist.',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must exist.',
        ],
    ];
}
