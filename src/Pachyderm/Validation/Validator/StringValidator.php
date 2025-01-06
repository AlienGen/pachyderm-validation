<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class StringValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        return is_string($value);
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be a string.';
    }
}
