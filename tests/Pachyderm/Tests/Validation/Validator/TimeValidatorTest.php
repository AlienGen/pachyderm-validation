<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\TimeValidator;

class TimeValidatorTest extends TestCase
{
    private TimeValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new TimeValidator();
    }

    public function testValidTime(): void
    {
        $this->assertTrue($this->validator->validate('00:00'));
        $this->assertTrue($this->validator->validate('23:59'));
        $this->assertTrue($this->validator->validate('12:30'));
        $this->assertTrue($this->validator->validate('09:05'));
        $this->assertTrue($this->validator->validate('14:30:45')); // With seconds
    }

    public function testInvalidTime(): void
    {
        $this->assertFalse($this->validator->validate('24:00')); // Invalid hour
        $this->assertFalse($this->validator->validate('23:60')); // Invalid minute
        $this->assertFalse($this->validator->validate('9:5')); // Missing leading zeros
        $this->assertFalse($this->validator->validate('12:30:61')); // Invalid seconds
        $this->assertFalse($this->validator->validate('invalid'));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 