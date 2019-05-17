<?php

namespace Ycs77\LaravelFormFieldType\Test;

use FieldType;
use FormBuilder;
use Ycs77\LaravelFormFieldType\Exceptions\LaravelFormFieldTypeException;

class FieldTypeTest extends TestCase
{
    public function testType()
    {
        // arrange
        $expected = [
            'id' => 'name',
            'type' => 'text',
            'rules' => 'required|max:20',
        ];

        // act
        $actual = FieldType::type('name');

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testOverrideTypeFromValue()
    {
        // arrange
        $expected = [
            'id' => 'nickname',
            'type' => 'text',
            'rules' => 'required|max:20',
        ];

        // act
        $actual = FieldType::type('nickname', 'name');

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testOverrideTypeFromValueType()
    {
        // arrange
        $expected = [
            'id' => 'nickname',
            'type' => 'text',
            'rules' => 'required|max:20',
        ];

        // act
        $actual = FieldType::type('nickname', [
            'type' => 'name',
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testOverrideTypeFromName()
    {
        // arrange
        $expected = [
            'id' => 'name',
            'type' => 'text',
            'rules' => 'required',
        ];

        // act
        $actual = FieldType::type('name', [
            'rules' => 'required',
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testTypeMethodUseFrontRules()
    {
        // arrange
        $expected = [
            'id' => 'name',
            'type' => 'text',
            'rules' => 'required',
        ];

        // act
        $actual = FieldType::type('name', [
            'front_rules' => 'required',
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testFieldsMethod()
    {
        // arrange
        $expected = [
            [
                'id' => 'name',
                'type' => 'text',
                'rules' => 'required|max:20',
            ],
            [
                'id' => 'nickname',
                'type' => 'text',
                'rules' => 'required|max:20',
            ],
            [
                'id' => 'phone',
                'type' => 'tel',
                'rules' => 'required|max:20',
            ],
            [
                'id' => 'age',
                'type' => 'number',
            ],
        ];

        // act
        $actual = FieldType::fields([
            'name',
            'nickname' => 'name',
            'phone' => [
                'type' => 'phone',
                'rules' => 'required|max:20',
            ],
            'age' => [
                'type' => 'number',
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testFieldsMethodFieldValueIsNull()
    {
        $this->expectException(LaravelFormFieldTypeException::class);
        $this->expectExceptionMessage('The field value must type a String or Array.');

        FieldType::fields([
            'name' => null,
        ]);
    }

    public function testListMethod()
    {
        // arrange
        $expected = [
            'name',
            'age',
        ];

        // act
        $actual = FieldType::list([
            'name',
            'age' => [
                'type' => 'number',
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testMapMethod()
    {
        // arrange
        $expected = [
            'text',
            'number',
        ];

        // act
        $actual = FieldType::map([
            'name',
            'age' => [
                'type' => 'number',
            ],
        ], function ($value) {
            return $value['type'];
        });

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testCastsMethod()
    {
        // arrange
        $expected = [
            'meeting_time' => '2018-01-01 00:00:00',
        ];

        // act
        $actual = FieldType::casts([
            'name',
            'meeting_time' => [
                'type' => 'datetime-local',
                'rules' => 'required',
            ],
        ], [
            'meeting_time' => '2018-01-01T00:00',
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testRenderMethod()
    {
        // arrange
        $form     = FormBuilder::plain();
        $expected = FormBuilder::plain()
            ->add('name', 'text', [
                'rules' => 'required|max:20',
            ])
            ->add('meeting_time', 'datetime-local', [
                'rules' => 'required',
            ]);

        // act
        $actual = FieldType::render($form, [
            'name',
            'meeting_time' => [
                'type' => 'datetime-local',
                'rules' => 'required',
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    public function testRulesMethod()
    {
        // arrange
        $expected = [
            'name'  => 'required|max:20',
            'phone' => 'required',
            'age'   => 'required',
        ];

        // act
        $actual = FieldType::rules([
            'name',
            'phone' => [
                'rules' => 'required',
            ],
            'age' => [
                'back_rules' => 'required',
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }
}
