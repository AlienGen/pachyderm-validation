<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\EmailValidator;

class EmailValidatorTest extends TestCase
{
    private EmailValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new EmailValidator();
    }

    public function testValidEmail(): void
    {
        $this->assertTrue($this->validator->validate('test@example.com'));
        $this->assertTrue($this->validator->validate('test.name@example.com'));
        $this->assertTrue($this->validator->validate('test+label@example.com'));
        $this->assertTrue($this->validator->validate('test@subdomain.example.com'));
    }

    public function testInvalidEmail(): void
    {
        $this->assertFalse($this->validator->validate('invalid-email'));
        $this->assertFalse($this->validator->validate('test@'));
        $this->assertFalse($this->validator->validate('@example.com'));
        $this->assertFalse($this->validator->validate('test@.com'));
        $this->assertFalse($this->validator->validate('test@example'));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 