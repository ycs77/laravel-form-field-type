<?php

namespace Ycs77\LaravelFormFieldType\Test;

use FieldType;
use FormBuilder;

class FieldTypeTest extends TestCase
{
    /** @test */
    public function testTypeMethod()
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

    /** @test */
    public function testOverrideType()
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

    /** @test */
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

    /** @test */
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
                'id' => 'age',
                'type' => 'number',
            ],
        ];

        // act
        $actual = FieldType::fields([
            'name',
            'age' => [
                'type' => 'number',
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    /** @test */
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

    /** @test */
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

    /** @test */
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

    /** @test */
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

    /** @test */
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
