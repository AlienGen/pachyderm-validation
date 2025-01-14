<?php

namespace Pachyderm\Validation\Validator;

use DateTime;
use Pachyderm\Validation\ValidatorInterface;

class DateTimeValidator implements ValidatorInterface
{
    public function validate(mixed $value, array $options = []): bool
    {
        if($value === null) {
            return true;
        }

        $format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, $value);
        return $date && $date->format($format) === $value;
    }

    public function getErrorMessage(array $options = []): string
    {
        return 'The value must be a date and time with the format Y-m-d H:i:s.';
    }
}
