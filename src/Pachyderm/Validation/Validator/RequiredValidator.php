<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class RequiredValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_string($value)) {
            return trim($value) !== '';
        }

        if (is_array($value)) {
            return !empty($value);
        }

        // For boolean, integer, float, and object values
        return true;
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The field is required.';
    }
}
