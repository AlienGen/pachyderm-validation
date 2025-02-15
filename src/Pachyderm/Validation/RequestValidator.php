<?php

namespace Pachyderm\Validation;

use Pachyderm\Exceptions\ValidationException;
use Pachyderm\Exchange\Request;
use Pachyderm\Utils\IterableObjectSet;

/**
 * Abstract class RequestValidator
 * 
 * This class serves as a base for creating request validators. It extends
 * the Request class and requires implementing classes to define specific
 * validation rules for request fields. The constructor validates the request
 * body against these rules and throws a ValidationException if any validation
 * errors are detected.
 */
abstract class RequestValidator extends Request
{
    /**
     * Define validation rules for request fields.
     * 
     * The returned array should be an array where keys are field names and values are validation rules.
     * 
     * Here is an example of the rules array:
     * [
     *     'email' => 'required|email',
     *     'user' => '', // This will be validated as an optional field. The nested fields will not be validated if the user field is not present.
     *     'user.name' => 'required|min:10',
     *     'user.age' => 'required|integer|min:18|max:65',
     *     'friends' => 'array',
     *     'friends.*.name' => 'required|string',
     *     'friends.*.email' => 'required|email',
     * ]
     * 
     * @return array An associative array where keys are field names and values are validation rules.
     */
    abstract public function rules(): array;

    /**
     * Constructor for RequestValidator.
     * 
     * @param mixed $body The request body to be validated.
     * 
     * @throws ValidationException if validation errors are found.
     */
    public function __construct(mixed $body)
    {
        parent::__construct($body);

        // Retrieve validation rules from the implementing class
        $fieldsRules = $this->rules();

        // Validate the request body against the rules
        $errors = Validator::validate($fieldsRules, $body);

        // If there are any validation errors, throw a ValidationException
        if (count($errors) > 0) {
            throw new ValidationException('Validation failed', $errors);
        }
    }
}
