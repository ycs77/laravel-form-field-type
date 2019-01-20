<?php

namespace Ycs77\LaravelFormFieldType\Test;

use FieldType;
use FormBuilder;

class FieldTypeTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->request = $this->app['request'];
        $this->request->setLaravelSession($this->app['session.store']);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('field.types', [
            'name' => [
                'type' => 'text',
                'rules' => 'required|max:20',
            ],
            'phone' => [
                'type' => 'tel',
                'rules' => 'required|max:15',
            ],
        ]);

        $app['config']->set('laravel-form-builder', [
            'custom_fields' => [
                'checkable_group' => '\App\Forms\Fields\CheckableGroupType',
            ],
        ]);
    }

    /** @test */
    public function test_type_method()
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
    public function test_override_type()
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
    public function test_fields_method()
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
    public function test_list_method()
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
    public function test_map_method()
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
    public function test_casts_method()
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
    public function test_render_method()
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
    public function test_render_checkbox_group()
    {
        // arrange
        $form     = FormBuilder::plain();
        $expected = FormBuilder::plain()
            ->add('checkbox_group_field', 'checkable_group', [
                'class' => FormBuilder::plain()
                    ->add('checkbox_1', 'checkbox', [
                        'label' => 'Checkbox 1',
                        'value' => 'Checkbox 1',
                    ])
                    ->add('checkbox_2', 'checkbox', [
                        'label' => 'Checkbox 2',
                        'value' => 'Checkbox 2',
                    ])
                    ->add('checkbox_3', 'checkbox', [
                        'label' => 'Checkbox 3',
                        'value' => 'Checkbox 3',
                    ]),
            ]);

        // act
        $actual = FieldType::render($form, [
            'checkbox_group_field' => [
                'type' => 'checkbox_group',
                'options' => [
                    'checkbox_1' => 'Checkbox 1',
                    'checkbox_2' => 'Checkbox 2',
                    'checkbox_3' => 'Checkbox 3',
                ],
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_rules_method()
    {
        // arrange
        $expected = [
            'name' => 'required|max:20',
            'age'  => 'required',
        ];

        // act
        $actual = FieldType::rules([
            'name',
            'age' => [
                'type'  => 'number',
                'rules' => 'required',
            ],
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }
}
