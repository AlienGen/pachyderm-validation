<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class NumericValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        return is_numeric($value);
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be numeric.';
    }
}
