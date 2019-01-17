<?php

namespace Ycs77\LaravelFormFieldType\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array type(string $type, array $data = [])
 * @method static array field(array|string $value)
 * @method static array fields(array $fields)
 * @method static array list(array $fields)
 * @method static array casts(array $fields, array $data)
 * @method static \Kris\LaravelFormBuilder\Form render(\Kris\LaravelFormBuilder\Form $form, array $fields_data)
 * @method static array rules(array $fields)
 *
 * @see \Ycs77\LaravelFormFieldType\FieldType
 */
class FieldType extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-form-field-type';
    }
}
