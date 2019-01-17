<?php

namespace Ycs77\LaravelFormFieldType;

use Illuminate\Support\ServiceProvider;

class FieldTypeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('Ycs77\LaravelFormFieldType\FieldType', function ($app) {
            return new \Ycs77\LaravelFormFieldType\FieldType($app);
        });

        $this->app->alias('Ycs77\LaravelFormFieldType\FieldType', 'laravel-form-field-type');

        $this->publishes([
            __DIR__ . '/../config/field.php' => config_path('field.php'),
        ], 'laravel-form-field-type-config');

        $this->publishes([
            __DIR__ . '/Fields/CheckableGroupType.php' => app_path('Forms/Fields/CheckableGroupType.php'),
        ], 'laravel-form-checkable-group-type');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/field.php',
            'field'
        );
    }
}
