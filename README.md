# Pachyderm Validation

Pachyderm Validation is a PHP library for Pachyderm, designed to provide a robust and flexible validation framework for your applications. It offers a wide range of built-in validators and allows for easy extension to meet custom validation needs.

## Features

- **Comprehensive Validators**: Includes a variety of validators such as `Required`, `Email`, `Min`, `Max`, `Between`, `Regex`, and more.
- **Customizable**: Easily extend the library with custom validators.
- **Nested Validation**: Supports validation of nested fields using dot notation.
- **Exception Handling**: Throws detailed exceptions for validation errors.

## Installation

To install the library, use Composer:

```bash
composer require pachyderm/validation
```

## Usage

To use the library, you can create a new instance of the `Validator` class and pass the data you want to validate to the `validate` method.

```php
use Pachyderm\Validation\Validator;
$validator = Validator::getInstance();
$errors = $validator->validate('required|email', $value);
if (!empty($errors)) {
// Handle validation errors
}
```

### Request Validation

For validating request data, extend the `RequestValidator` class and define your rules:

```php
use Pachyderm\Validation\RequestValidator;

class MyRequestValidator extends RequestValidator
{
    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'age' => 'required|integer|min:18',
            'email' => 'required|email'
        ];
    }
}
```

### Custom Validators

To create a custom validator, implement the `ValidatorInterface`:

```php
use Pachyderm\Validation\ValidatorInterface;

class MyCustomValidator implements ValidatorInterface
{
    public function validate($value, $rule): bool
    {
        // Implement your custom validation logic here
        return $value === 'custom';
    }
}
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
