<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\NumericValidator;

class NumericValidatorTest extends TestCase
{
    private NumericValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new NumericValidator();
    }

    public function testValidNumeric(): void
    {
        $this->assertTrue($this->validator->validate('123'));
        $this->assertTrue($this->validator->validate('123.45'));
        $this->assertTrue($this->validator->validate('-123'));
        $this->assertTrue($this->validator->validate('-123.45'));
        $this->assertTrue($this->validator->validate('0'));
        $this->assertTrue($this->validator->validate(123));
        $this->assertTrue($this->validator->validate(123.45));
    }

    public function testInvalidNumeric(): void
    {
        $this->assertFalse($this->validator->validate('abc'));
        $this->assertFalse($this->validator->validate('123abc'));
        $this->assertFalse($this->validator->validate(''));
        $this->assertFalse($this->validator->validate('12.34.56'));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 