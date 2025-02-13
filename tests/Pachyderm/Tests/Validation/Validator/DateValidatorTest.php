<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\DateValidator;

class DateValidatorTest extends TestCase
{
    private DateValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new DateValidator();
    }

    public function testValidDate(): void
    {
        $this->assertTrue($this->validator->validate('2024-03-20'));
        $this->assertTrue($this->validator->validate('2024-12-31'));
        $this->assertTrue($this->validator->validate('2024-02-29')); // Leap year
    }

    public function testInvalidDate(): void
    {
        $this->assertFalse($this->validator->validate('2024-13-01')); // Invalid month
        $this->assertFalse($this->validator->validate('2024-04-31')); // Invalid day
        $this->assertFalse($this->validator->validate('2023-02-29')); // Not a leap year
        $this->assertFalse($this->validator->validate('invalid-date'));
        $this->assertFalse($this->validator->validate('2024/03/20')); // Wrong format
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 