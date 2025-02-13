<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\MaxValidator;

class MaxValidatorTest extends TestCase
{
    private MaxValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new MaxValidator();
    }

    public function testValidMax(): void
    {
        $this->assertTrue($this->validator->validate(5, [10]));
        $this->assertTrue($this->validator->validate(10, [10])); // Boundary
        $this->assertTrue($this->validator->validate(-5, [10]));
        $this->assertTrue($this->validator->validate(5.5, [10]));
    }

    public function testInvalidMax(): void
    {
        $this->assertFalse($this->validator->validate(11, [10]));
        $this->assertFalse($this->validator->validate(10.1, [10]));
        $this->assertFalse($this->validator->validate(100, [10]));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null, [10]));
    }
} 