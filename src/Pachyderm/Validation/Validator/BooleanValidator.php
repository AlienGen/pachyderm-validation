<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class BooleanValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        return is_bool($value);
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be a boolean.';
    }
}
