<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\DecimalValidator;

class DecimalValidatorTest extends TestCase
{
    private DecimalValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new DecimalValidator();
    }

    public function testValidDecimal(): void
    {
        $this->assertTrue($this->validator->validate('1.5'));
        $this->assertTrue($this->validator->validate('0.0'));
        $this->assertTrue($this->validator->validate('-1.5'));
        $this->assertTrue($this->validator->validate('1234.5678'));
    }

    public function testInvalidDecimal(): void
    {
        $this->assertFalse($this->validator->validate('123')); // Integer
        $this->assertFalse($this->validator->validate('abc'));
        $this->assertFalse($this->validator->validate('1.2.3'));
        $this->assertFalse($this->validator->validate('.5')); // Missing leading zero
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 