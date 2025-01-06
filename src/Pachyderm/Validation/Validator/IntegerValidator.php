<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class IntegerValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        return is_numeric($value) && strpos($value, '.') === false;
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be an integer.';
    }
}
