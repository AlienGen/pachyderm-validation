<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\StringValidator;

class StringValidatorTest extends TestCase
{
    private StringValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new StringValidator();
    }

    public function testValidString(): void
    {
        $this->assertTrue($this->validator->validate(''));
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate('123'));
        $this->assertTrue($this->validator->validate('abc 123'));
        $this->assertTrue($this->validator->validate('!@#$%^&*()'));
    }

    public function testInvalidString(): void
    {
        $this->assertFalse($this->validator->validate(123));
        $this->assertFalse($this->validator->validate(12.34));
        $this->assertFalse($this->validator->validate(true));
        $this->assertFalse($this->validator->validate([]));
        $this->assertFalse($this->validator->validate(new \stdClass()));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 