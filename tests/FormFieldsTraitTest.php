<?php

namespace Ycs77\LaravelFormFieldType\Test;

use Ycs77\LaravelFormFieldType\Traits\FormFieldsTrait;

class FormFieldsTraitTest extends TestCase
{
    /** @test */
    public function testGetFormFieldsMethod()
    {
        // arrange
        $mock = $this->getMockForTrait(FormFieldsTrait::class);
        $expected = [
            'name',
        ];

        // act
        $actual = $mock->getFormFields([
            'name',
        ]);

        // assert
        $this->assertEquals($expected, $actual);
    }
}
