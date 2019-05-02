<?php

namespace Ycs77\LaravelFormFieldType\Test;

use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Ycs77\LaravelFormFieldType\Facades\FieldType;
use Ycs77\LaravelFormFieldType\Fields\CheckableGroupType;
use Ycs77\LaravelFormFieldType\FieldTypeServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
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
                'checkable_group' => CheckableGroupType::class,
            ],
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            FieldTypeServiceProvider::class,
            FormBuilderServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'FieldType' => FieldType::class,
            'FormBuilder' => FormBuilder::class,
        ];
    }
}
