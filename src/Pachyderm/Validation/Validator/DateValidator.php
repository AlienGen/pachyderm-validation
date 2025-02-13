<?php

namespace Pachyderm\Validation\Validator;

use DateTime;
use Pachyderm\Validation\ValidatorInterface;

class DateValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        $date = DateTime::createFromFormat('Y-m-d', $value);
        return $date && $date->format('Y-m-d') === $value;
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be a date in the format YYYY-MM-DD.';
    }
}
