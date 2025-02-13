<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\RegexValidator;

class RegexValidatorTest extends TestCase
{
    private RegexValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new RegexValidator();
    }

    public function testValidRegex(): void
    {
        $this->assertTrue($this->validator->validate('abc123', ['/^[a-z0-9]+$/']));
        $this->assertTrue($this->validator->validate('test@example.com', ['/^.+@.+\..+$/']));
        $this->assertTrue($this->validator->validate('123-456-7890', ['/^\d{3}-\d{3}-\d{4}$/']));
    }

    public function testInvalidRegex(): void
    {
        $this->assertFalse($this->validator->validate('abc 123', ['/^[a-z0-9]+$/']));
        $this->assertFalse($this->validator->validate('invalid-email', ['/^.+@.+\..+$/']));
        $this->assertFalse($this->validator->validate('123.456.7890', ['/^\d{3}-\d{3}-\d{4}$/']));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null, ['/^[a-z0-9]+$/']));
    }
} 