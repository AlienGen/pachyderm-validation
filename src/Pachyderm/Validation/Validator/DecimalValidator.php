<?php

namespace Pachyderm\Validation\Validator;

use Pachyderm\Validation\ValidatorInterface;

class DecimalValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        // Convert to string if numeric
        if (is_numeric($value)) {
            $value = (string)$value;
        }

        // Must be a string at this point
        if (!is_string($value)) {
            return false;
        }

        // Must contain a decimal point
        if (strpos($value, '.') === false) {
            return false;
        }

        // Must be a valid decimal number format
        if (!preg_match('/^-?\d+\.\d+$/', $value)) {
            return false;
        }

        return true;
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be a decimal number.';
    }
}
