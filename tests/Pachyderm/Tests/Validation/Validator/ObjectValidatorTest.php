<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\ObjectValidator;
use stdClass;

class ObjectValidatorTest extends TestCase
{
    private ObjectValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ObjectValidator();
    }

    public function testValidObject(): void
    {
        $this->assertTrue($this->validator->validate(new stdClass()));
        $this->assertTrue($this->validator->validate((object)['key' => 'value']));
        $this->assertTrue($this->validator->validate($this->validator));
    }

    public function testInvalidObject(): void
    {
        $this->assertFalse($this->validator->validate('string'));
        $this->assertFalse($this->validator->validate(123));
        $this->assertFalse($this->validator->validate([]));
        $this->assertFalse($this->validator->validate(['key' => 'value']));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 