<?php

namespace Pachyderm\Validation;

use Pachyderm\Validation\Validator\AlphaNumValidator;
use Pachyderm\Validation\Validator\AlphaValidator;
use Pachyderm\Validation\Validator\ArrayValidator;
use Pachyderm\Validation\Validator\BetweenValidator;
use Pachyderm\Validation\Validator\BooleanValidator;
use Pachyderm\Validation\Validator\DateFormatValidator;
use Pachyderm\Validation\Validator\DateValidator;
use Pachyderm\Validation\Validator\DecimalValidator;
use Pachyderm\Validation\Validator\EmailValidator;
use Pachyderm\Validation\Validator\IntegerValidator;
use Pachyderm\Validation\Validator\InValidator;
use Pachyderm\Validation\Validator\MaxValidator;
use Pachyderm\Validation\Validator\MinValidator;
use Pachyderm\Validation\Validator\NotInValidator;
use Pachyderm\Validation\Validator\NumericValidator;
use Pachyderm\Validation\Validator\ObjectValidator;
use Pachyderm\Validation\Validator\RegexValidator;
use Pachyderm\Validation\Validator\RequiredValidator;
use Pachyderm\Validation\Validator\StringValidator;
use Pachyderm\Validation\Validator\TimeValidator;
use Pachyderm\Validation\Validator\TimezoneValidator;
use Exception;
use Pachyderm\Validation\Validator\DateTimeValidator;

class Validator
{
    private static $validator = null;
    protected $validators = [];

    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }

    public function validateValue(string $rules, mixed $value): array
    {
        $rules = explode('|', $rules);
        $errors = [];

        // Handle required validation first
        $isRequired = in_array('required', $rules);
        $isArrayRule = in_array('array', $rules);

        // Skip validation if value is null and not required
        if (!$isRequired && $value === null) {
            return [];
        }

        // For array validation, only validate if value exists
        if ($isArrayRule && !$isRequired && !$value) {
            return [];
        }

        foreach ($rules as $rule) {
            $rule = explode(':', $rule);
            $validatorName = $rule[0];
            $options = isset($rule[1]) ? explode(',', $rule[1]) : [];

            // Skip validation for non-required null values except for required validator
            if ($validatorName !== 'required' && !$isRequired && $value === null) {
                continue;
            }

            // Check if the validator exists
            if (!isset($this->validators[$validatorName])) {
                throw new Exception("Validator '{$validatorName}' not found.");
            }

            if (!$this->validators[$validatorName]->validate($value, $options)) {
                $errors[] = $this->validators[$validatorName]->getErrorMessage($options);
            }
        }

        return $errors;
    }

    public static function getInstance(): Validator
    {
        if (self::$validator === null) {
            self::$validator = new Validator([
                'required' => new RequiredValidator(),
                'email' => new EmailValidator(),
                'min' => new MinValidator(),
                'max' => new MaxValidator(),
                'between' => new BetweenValidator(),
                'in' => new InValidator(),
                'not_in' => new NotInValidator(),
                'regex' => new RegexValidator(),
                'string' => new StringValidator(),
                'alpha' => new AlphaValidator(),
                'alpha_num' => new AlphaNumValidator(),
                'numeric' => new NumericValidator(),
                'integer' => new IntegerValidator(),
                'decimal' => new DecimalValidator(),
                'boolean' => new BooleanValidator(),
                'array' => new ArrayValidator(),
                'object' => new ObjectValidator(),
                'date' => new DateValidator(),
                'date_format' => new DateFormatValidator(),
                'time' => new TimeValidator(),
                'datetime' => new DateTimeValidator(),
                'timezone' => new TimezoneValidator(),
            ]);
        }

        return self::$validator;
    }

    public static function validate(array $rules, array $data): array
    {
        $validator = self::getInstance();
        $errors = [];

        foreach ($rules as $field => $ruleSet) {
            $validator->validateField($field, $ruleSet, $data, $rules, $errors);
        }

        return $errors;
    }

    protected function validateField(string $field, string $ruleSet, array $data, array $rules, array &$errors): void
    {
        $segments = explode('.', $field);
        $root = $segments[0];

        // For non-array fields (no wildcards)
        if (!str_contains($field, '*')) {
            if (!str_contains($ruleSet, 'required') && !array_key_exists($root, $data)) {
                return;
            }

            $value = $this->getFieldValue($data, $field);
            $fieldErrors = $this->validateValue($ruleSet, $value);
            if (!empty($fieldErrors)) {
                $errors[$field] = $fieldErrors;
            }
            return;
        }

        // For fields with wildcards:
        $value = $this->getFieldValue($data, $root);

        // If the root field doesn't exist but is required, add error
        if (!array_key_exists($root, $data)) {
            if (str_contains($rules[$root] ?? '', 'required')) {
                $errors[$root] = ['The field is required.'];
            }
            return;
        }
        
        // Must be an array
        if (!is_array($value)) {
            $errors[$root] = ['The field must be an array.'];
            return;
        }
        
        // Use the new recursive helper starting from the root.
        // Remove the root segment (already validated) so that the helper receives the remainder.
        $this->recursiveValidateNested($value, array_slice($segments, 1), $ruleSet, $errors, [$root]);
    }

    /**
     * Recursively validates nested fields using dot-notation segments.
     * Wildcard segments ("*") will iterate over arrays.
     * The complete error key (built from $prefix) will not contain any wildcards.
     */
    protected function recursiveValidateNested($data, array $segments, string $ruleSet, array &$errors, array $prefix): void
    {
        // If no segments remain, we've reached the leaf; validate the value.
        if (empty($segments)) {
            $fieldErrors = $this->validateValue($ruleSet, $data);
            if (!empty($fieldErrors)) {
                $errors[implode('.', $prefix)] = $fieldErrors;
            }
            return;
        }
        
        // Get the next segment (which can be a literal key or a wildcard "*")
        $segment = array_shift($segments);
        if ($segment === '*') {
            // Expect $data to be an array; iterate its items.
            if (!is_array($data)) {
                // If required and data is not an array, add an error.
                if (str_contains($ruleSet, 'required')) {
                    $errors[implode('.', $prefix)] = ['The field is required.'];
                }
                return;
            }
            foreach ($data as $index => $item) {
                // For each item, extend the prefix with the current index.
                $newPrefix = array_merge($prefix, [$index]);
                // Recurse with the same remaining segments.
                $this->recursiveValidateNested($item, $segments, $ruleSet, $errors, $newPrefix);
            }
        } else {
            // A literal segment.
            if (is_array($data) && array_key_exists($segment, $data)) {
                $newPrefix = array_merge($prefix, [$segment]);
                $this->recursiveValidateNested($data[$segment], $segments, $ruleSet, $errors, $newPrefix);
            } else {
                // Key not found – if the rule is 'required', add an error.
                if (str_contains($ruleSet, 'required')) {
                    $errors[implode('.', array_merge($prefix, [$segment]))] = ['The field is required.'];
                }
            }
        }
    }

    protected function getFieldValue(?array $data, string $field)
    {
        if ($data === null || empty($field)) {
            return null;
        }

        $segments = explode('.', $field);
        $value = $data;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public static function addValidator(string $name, ValidatorInterface $validator)
    {
        self::$validators[$name] = $validator;
    }
}
