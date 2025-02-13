<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\AlphaNumValidator;

class AlphaNumValidatorTest extends TestCase
{
    private AlphaNumValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new AlphaNumValidator();
    }

    public function testValidAlphaNum(): void
    {
        $this->assertTrue($this->validator->validate('abc123'));
        $this->assertTrue($this->validator->validate('ABC123'));
        $this->assertTrue($this->validator->validate('123abc'));
    }

    public function testInvalidAlphaNum(): void
    {
        $this->assertFalse($this->validator->validate('abc 123'));
        $this->assertFalse($this->validator->validate('abc-123'));
        $this->assertFalse($this->validator->validate('abc_123'));
        $this->assertFalse($this->validator->validate('abc@123'));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 