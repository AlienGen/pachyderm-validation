<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\IntegerValidator;

class IntegerValidatorTest extends TestCase
{
    private IntegerValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new IntegerValidator();
    }

    public function testValidInteger(): void
    {
        $this->assertTrue($this->validator->validate('123'));
        $this->assertTrue($this->validator->validate('-123'));
        $this->assertTrue($this->validator->validate('0'));
        $this->assertTrue($this->validator->validate(123));
    }

    public function testInvalidInteger(): void
    {
        $this->assertFalse($this->validator->validate('1.5'));
        $this->assertFalse($this->validator->validate('abc'));
        $this->assertFalse($this->validator->validate('123abc'));
        $this->assertFalse($this->validator->validate(''));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 