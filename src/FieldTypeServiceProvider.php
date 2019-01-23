<?php

namespace Ycs77\LaravelFormFieldType;

use Illuminate\Support\ServiceProvider;
use Ycs77\LaravelFormFieldType\FieldType;

class FieldTypeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/field.php' => config_path('field.php'),
        ], 'laravel-form-field-type-config');

        $this->publishes([
            __DIR__ . '/Fields/CheckableGroupType.php' => app_path('Forms/Fields/CheckableGroupType.php'),
            __DIR__ . '/../resources/views/checkable_group.php' => resource_path('views/vendor/laravel-form-builder/checkable_group.php'),
        ], 'laravel-form-checkable-group-type');
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
