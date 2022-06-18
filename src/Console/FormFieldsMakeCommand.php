<?php

namespace Ycs77\LaravelFormFieldType\Console;

use Illuminate\Console\GeneratorCommand;

class FormFieldsMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:formfields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form fields class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'FormFields';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/form-fields.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\FormFields';
    }
}
