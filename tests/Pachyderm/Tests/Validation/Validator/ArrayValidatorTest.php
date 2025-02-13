<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\ArrayValidator;

class ArrayValidatorTest extends TestCase
{
    private ArrayValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ArrayValidator();
    }

    public function testValidArray(): void
    {
        $this->assertTrue($this->validator->validate([]));
        $this->assertTrue($this->validator->validate(['test']));
        $this->assertTrue($this->validator->validate(['key' => 'value']));
    }

    public function testInvalidArray(): void
    {
        $this->assertFalse($this->validator->validate('string'));
        $this->assertFalse($this->validator->validate(123));
        $this->assertFalse($this->validator->validate(new \stdClass()));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }

    public function testErrorMessage(): void
    {
        $this->assertNotEmpty($this->validator->getErrorMessage());
    }
} 