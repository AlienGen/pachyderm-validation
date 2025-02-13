<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\RequiredValidator;

class RequiredValidatorTest extends TestCase
{
    private RequiredValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new RequiredValidator();
    }

    public function testValidRequired(): void
    {
        $this->assertTrue($this->validator->validate('value'));
        $this->assertTrue($this->validator->validate(0));
        $this->assertTrue($this->validator->validate(false));
        $this->assertTrue($this->validator->validate(['value']));
        $this->assertTrue($this->validator->validate(new \stdClass()));
    }

    public function testInvalidRequired(): void
    {
        $this->assertFalse($this->validator->validate(null));
        $this->assertFalse($this->validator->validate(''));
        $this->assertFalse($this->validator->validate([]));
    }
} 