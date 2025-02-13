<?php

namespace Pachyderm\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Pachyderm\Validation\Validator;

class ValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = Validator::getInstance();
    }

    public function testBasicValidation(): void
    {
        $errors = $this->validator->validateValue('required|email', 'test@example.com');
        $this->assertEmpty($errors);

        $errors = $this->validator->validateValue('required|email', 'invalid-email');
        $this->assertNotEmpty($errors);
    }

    public function testArrayValidation(): void
    {
        $rules = [
            'items' => 'array',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string|min:3',
            'items.*.price' => 'required|numeric|min:0'
        ];

        // Empty array should pass
        $validData = [];
    
        $errors = Validator::validate($rules, $validData);
        $this->assertEmpty($errors);

        // Valid array should pass
        $validData = [
            'items' => [
                [
                    'id' => 1,
                    'name' => 'Product 1',
                    'price' => 29.99
                ],
                [
                    'id' => 2,
                    'name' => 'Product 2',
                    'price' => 49.99
                ]
            ]
        ];

        $errors = Validator::validate($rules, $validData);
        $this->assertEmpty($errors);

        $invalidData = [
            'items' => [
                [
                    'id' => 'not-an-integer',
                    'name' => 'P', // too short
                    'price' => -10 // negative price
                ]
            ]
        ];

        $errors = Validator::validate($rules, $invalidData);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('items.0.id', $errors);
        $this->assertArrayHasKey('items.0.name', $errors);
        $this->assertArrayHasKey('items.0.price', $errors);
    }

    public function testDeepNestedArrayValidation(): void
    {
        $rules = [
            'categories' => 'required|array',
            'categories.*.name' => 'required|string|min:3',
            'categories.*.products' => 'required|array',
            'categories.*.products.*.id' => 'required|integer',
            'categories.*.products.*.variants' => 'required|array',
            'categories.*.products.*.variants.*.sku' => 'required|string',
            'categories.*.products.*.variants.*.price' => 'required|numeric|min:0'
        ];

        $validData = [
            'categories' => [
                [
                    'name' => 'Electronics',
                    'products' => [
                        [
                            'id' => 1,
                            'variants' => [
                                ['sku' => 'ELEC-001-BLK', 'price' => 299.99],
                                ['sku' => 'ELEC-001-WHT', 'price' => 299.99]
                            ]
                        ],
                        [
                            'id' => 2,
                            'variants' => [
                                ['sku' => 'ELEC-002-GRY', 'price' => 199.99]
                            ]
                        ]
                    ]
                ],
                [
                    'name' => 'Clothing',
                    'products' => [
                        [
                            'id' => 3,
                            'variants' => [
                                ['sku' => 'CLT-001-S', 'price' => 49.99],
                                ['sku' => 'CLT-001-M', 'price' => 49.99],
                                ['sku' => 'CLT-001-L', 'price' => 54.99]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $errors = Validator::validate($rules, $validData);
        $this->assertEmpty($errors);

        // Empty array should not pass
        $invalidData = [];
        $errors = Validator::validate($rules, $invalidData);
        $this->assertNotEmpty($errors);

        $invalidData = [
            'categories' => []
        ];
        $errors = Validator::validate($rules, $invalidData);
        $this->assertNotEmpty($errors);

        // Invalid data should not pass
        $invalidData = [
            'categories' => [
                [
                    'name' => 'El', // too short
                    'products' => [
                        [
                            'id' => 'invalid-id', // not an integer
                            'variants' => [
                                ['sku' => '', 'price' => -10], // empty sku and negative price
                                ['price' => 299.99] // missing sku
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $errors = Validator::validate($rules, $invalidData);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('categories.0.name', $errors);
        $this->assertArrayHasKey('categories.0.products.0.id', $errors);
        $this->assertArrayHasKey('categories.0.products.0.variants.0.sku', $errors);
        $this->assertArrayHasKey('categories.0.products.0.variants.0.price', $errors);
        $this->assertArrayHasKey('categories.0.products.0.variants.1.sku', $errors);
    }

    public function testNestedObjectValidation(): void
    {
        $rules = [
            'user' => 'required|array',
            'user.profile' => 'required|array',
            'user.profile.personal' => 'required|array',
            'user.profile.personal.firstName' => 'required|string|min:2',
            'user.profile.personal.lastName' => 'required|string|min:2',
            'user.profile.contact' => 'required|array',
            'user.profile.contact.email' => 'required|email',
            'user.profile.contact.phones' => 'required|array',
            'user.profile.contact.phones.*.number' => 'required|string',
            'user.profile.contact.phones.*.type' => 'required|in:home,work,mobile'
        ];

        $validData = [
            'user' => [
                'profile' => [
                    'personal' => [
                        'firstName' => 'John',
                        'lastName' => 'Doe'
                    ],
                    'contact' => [
                        'email' => 'john@example.com',
                        'phones' => [
                            ['number' => '123-456-7890', 'type' => 'home'],
                            ['number' => '098-765-4321', 'type' => 'mobile']
                        ]
                    ]
                ]
            ]
        ];

        $errors = Validator::validate($rules, $validData);
        $this->assertEmpty($errors);

        $invalidData = [
            'user' => [
                'profile' => [
                    'personal' => [
                        'firstName' => 'J', // too short
                        'lastName' => '' // empty
                    ],
                    'contact' => [
                        'email' => 'invalid-email',
                        'phones' => [
                            ['number' => '', 'type' => 'invalid-type'], // empty number and invalid type
                            ['type' => 'mobile'] // missing number
                        ]
                    ]
                ]
            ]
        ];

        $errors = Validator::validate($rules, $invalidData);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('user.profile.personal.firstName', $errors);
        $this->assertArrayHasKey('user.profile.personal.lastName', $errors);
        $this->assertArrayHasKey('user.profile.contact.email', $errors);
        $this->assertArrayHasKey('user.profile.contact.phones.0.number', $errors);
        $this->assertArrayHasKey('user.profile.contact.phones.0.type', $errors);
        $this->assertArrayHasKey('user.profile.contact.phones.1.number', $errors);
    }

    public function testCustomValidator(): void
    {
        $rules = [
            'custom_field' => 'required|in:value1,value2,value3'
        ];

        $validData = ['custom_field' => 'value1'];
        $errors = Validator::validate($rules, $validData);
        $this->assertEmpty($errors);

        $invalidData = ['custom_field' => 'invalid_value'];
        $errors = Validator::validate($rules, $invalidData);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('custom_field', $errors);
    }

    public function testOptionalFields(): void
    {
        $rules = [
            'optional_field' => 'string|min:3',
            'required_field' => 'required|string'
        ];

        // Optional field missing should pass
        $data = [
            'required_field' => 'value'
        ];
        $errors = Validator::validate($rules, $data);
        $this->assertEmpty($errors);

        // Optional field present but invalid should fail
        $data = [
            'required_field' => 'value',
            'optional_field' => 'ab'
        ];
        $errors = Validator::validate($rules, $data);
        $this->assertNotEmpty($errors);
        $this->assertArrayHasKey('optional_field', $errors);
    }
} 