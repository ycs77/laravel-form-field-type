<?php

namespace Ycs77\LaravelFormFieldType\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            'Ycs77\LaravelFormFieldType\FieldTypeServiceProvider',
            'Kris\LaravelFormBuilder\FormBuilderServiceProvider',
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'FieldType' => 'Ycs77\LaravelFormFieldType\Facades\FieldType',
            'FormBuilder' => 'Kris\LaravelFormBuilder\Facades\FormBuilder',
        ];
    }
}
