<?php

namespace Ycs77\LaravelFormFieldType;

use Illuminate\Support\ServiceProvider;
use Ycs77\LaravelFormFieldType\Console\FormFieldsMakeCommand;

class FieldTypeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands(FormFieldsMakeCommand::class);

        $this->publishes([
            __DIR__ . '/../config/field.php' => config_path('field.php'),
        ], 'laravel-form-field-type-config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laravel-form-field-type', function ($app) {
            return new FieldType($app);
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/field.php', 'field');
    }
}
