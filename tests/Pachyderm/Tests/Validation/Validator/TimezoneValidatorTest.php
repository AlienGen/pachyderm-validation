<?php

namespace Pachyderm\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator\TimezoneValidator;

class TimezoneValidatorTest extends TestCase
{
    private TimezoneValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new TimezoneValidator();
    }

    public function testValidTimezone(): void
    {
        $this->assertTrue($this->validator->validate('UTC'));
        $this->assertTrue($this->validator->validate('America/New_York'));
        $this->assertTrue($this->validator->validate('Europe/London'));
        $this->assertTrue($this->validator->validate('Asia/Tokyo'));
        $this->assertTrue($this->validator->validate('Australia/Sydney'));
    }

    public function testInvalidTimezone(): void
    {
        $this->assertFalse($this->validator->validate('Invalid/Timezone'));
        $this->assertFalse($this->validator->validate('GMT+2')); // Not in IANA format
        $this->assertFalse($this->validator->validate('EST'));
        $this->assertFalse($this->validator->validate('UTC+01:00'));
        $this->assertFalse($this->validator->validate(''));
    }

    public function testNullValue(): void
    {
        $this->assertTrue($this->validator->validate(null));
    }
} 