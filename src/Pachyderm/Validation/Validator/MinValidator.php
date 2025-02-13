<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class MinValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        if(!is_numeric($value) && is_string($value)) {
            $value = strlen($value) >= $options[0];
        }

        return $value >= $options[0];
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be greater than or equal to ' . $options[0] . '.';
    }
}
