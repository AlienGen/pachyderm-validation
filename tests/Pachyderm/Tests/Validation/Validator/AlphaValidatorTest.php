<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\AlphaValidator;

class AlphaValidatorTest extends TestCase
{
    private AlphaValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new AlphaValidator();
    }

    public function testValidAlpha(): void
    {
        $this->assertTrue($this->validator->validate('abc'));
        $this->assertTrue($this->validator->validate('ABC'));
        $this->assertTrue($this->validator->validate('abcDEF'));
    }

    public function testInvalidAlpha(): void
    {
        $this->assertFalse($this->validator->validate('abc123'));
        $this->assertFalse($this->validator->validate('abc '));
        $this->assertFalse($this->validator->validate('abc-def'));
        $this->assertFalse($this->validator->validate('abc@def'));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 