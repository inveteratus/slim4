<?php

namespace App\Classes;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    public function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $rule->setName(ucwords($field));
            $value = array_key_exists($field, $data) ? $data[$field] : null;
            try {
                $rule->assert($value);
            }
            catch (NestedValidationException $exception) {
                $messages = $exception->getMessages();
                $errors[$field] = array_shift($messages) . '.';
            }
        }

        return $errors;
    }
}
