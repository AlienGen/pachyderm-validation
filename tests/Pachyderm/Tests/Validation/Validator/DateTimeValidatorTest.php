<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\DateTimeValidator;

class DateTimeValidatorTest extends TestCase
{
    private DateTimeValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new DateTimeValidator();
    }

    public function testValidDateTime(): void
    {
        $this->assertTrue($this->validator->validate('2024-03-20 14:30:00'));
        $this->assertTrue($this->validator->validate('2024-12-31 23:59:59'));
        $this->assertTrue($this->validator->validate('2024-02-29 00:00:00')); // Leap year
    }

    public function testInvalidDateTime(): void
    {
        $this->assertFalse($this->validator->validate('2024-13-01 14:30:00')); // Invalid month
        $this->assertFalse($this->validator->validate('2024-03-20 24:00:00')); // Invalid hour
        $this->assertFalse($this->validator->validate('2024-03-20 14:60:00')); // Invalid minute
        $this->assertFalse($this->validator->validate('invalid-datetime'));
        $this->assertFalse($this->validator->validate('2024-03-20')); // Missing time
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 