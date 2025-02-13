<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\BetweenValidator;

class BetweenValidatorTest extends TestCase
{
    private BetweenValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new BetweenValidator();
    }

    public function testValidBetween(): void
    {
        $this->assertTrue($this->validator->validate(5, [1, 10]));
        $this->assertTrue($this->validator->validate(1, [1, 10])); // Min boundary
        $this->assertTrue($this->validator->validate(10, [1, 10])); // Max boundary
        $this->assertTrue($this->validator->validate(5.5, [1, 10]));
    }

    public function testInvalidBetween(): void
    {
        $this->assertFalse($this->validator->validate(0, [1, 10]));
        $this->assertFalse($this->validator->validate(11, [1, 10]));
        $this->assertFalse($this->validator->validate(-1, [1, 10]));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null, [1, 10]));
    }
} 