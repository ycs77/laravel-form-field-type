<?php

namespace Ycs77\LaravelFormFieldType\Test;

use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Ycs77\LaravelFormFieldType\Facades\FieldType;

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
    }

    protected function getPackageProviders($app)
    {
        return [
            \Collective\Html\HtmlServiceProvider::class,
            \Kris\LaravelFormBuilder\FormBuilderServiceProvider::class,
            \Ycs77\LaravelFormFieldType\FieldTypeServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'FieldType'   => FieldType::class,
            'Form'        => \Collective\Html\FormFacade::class,
            'FormBuilder' => FormBuilder::class,
        ];
    }
}
