<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\MinValidator;

class MinValidatorTest extends TestCase
{
    private MinValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new MinValidator();
    }

    public function testValidMin(): void
    {
        $this->assertTrue($this->validator->validate(15, [10]));
        $this->assertTrue($this->validator->validate(10, [10])); // Boundary
        $this->assertTrue($this->validator->validate(10.5, [10]));
    }

    public function testInvalidMin(): void
    {
        $this->assertFalse($this->validator->validate(9, [10]));
        $this->assertFalse($this->validator->validate(9.9, [10]));
        $this->assertFalse($this->validator->validate(-10, [10]));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null, [10]));
    }
} 