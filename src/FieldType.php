<?php

namespace Ycs77\LaravelFormFieldType;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Traits\Macroable;
use Kris\LaravelFormBuilder\Form;
use Ycs77\LaravelFormFieldType\Exceptions\LaravelFormFieldTypeException;

class FieldType
{
    use Macroable;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $formConfig;

    /**
     * Create new instance.
     */
    public function __construct($app)
    {
        $this->config = $app['config']->get('field');
        $this->formConfig = $app['config']->get('laravel-form-builder');
    }

    /**
     * Return the complete type of the specified type of data.
     *
     * @param  string  $name
     * @param  array|string  $data
     * @return array
     */
    public function type(string $name, $data = [])
    {
        $typeOption = [];
        $types = $this->config['types'];
        if (is_array($data)) {
            if (isset($data['type']) && isset($types[$data['type']])) {
                $typeOption = $types[$data['type']];
                unset($data['type']);
            } elseif (isset($types[$name])) {
                $typeOption = $types[$name];
            }
        } elseif (is_string($data)) {
            $typeOption = $this->getStringTypeOption($data);
            $data = [];
        }

        $field = array_merge(
            $typeOption,
            ['id' => $name],
            $data
        );

        if (isset($field['front_rules'])) {
            $field['rules'] = $field['front_rules'];
            unset($field['front_rules']);
        }

        return $field;
    }

    /**
     * Get string type option.
     *
     * @param  string  $type
     * @return array
     */
    protected function getStringTypeOption(string $type, $default = [])
    {
        return $this->config['types'][$type] ?? $default;
    }

    /**
     * Parsing field data.
     *
     * @param  array  $fields
     * @return array
     *
     * @throws \Ycs77\LaravelFormFieldType\Exceptions\LaravelFormFieldTypeException
     */
    public function fields(array $fields)
    {
        $result = [];

        foreach ($fields as $key => $value) {
            if (is_array($value) || is_string($key) && is_string($value)) {
                $result[] = $this->type($key, $value);
            } elseif (is_string($value)) {
                $result[] = $this->type($value);
            } else {
                throw new LaravelFormFieldTypeException('The field value must type a String or Array.');
            }
        }

        return $result;
    }

    /**
     * Return the ID of each field.
     *
     * @param  array  $fields
     * @return array
     */
    public function list(array $fields)
    {
        return array_map(function ($value) {
            return $value['id'];
        }, $this->fields($fields));
    }

    /**
     * Traversing the field.
     *
     * @param  array  $fields
     * @param  Closure  $collback
     * @return array
     */
    public function map(array $fields, Closure $collback)
    {
        return array_map($collback, $this->fields($fields));
    }

    /**
     * Transform to the right type.
     *
     * @param  array  $fields
     * @param  array  $data
     * @return array
     */
    public function casts(array $fields, array $data)
    {
        foreach ($this->fields($fields) as $field) {
            $id = $field['id'];
            switch ($field['type']) {
                case 'number':
                case 'range':
                    $data[$id] = (int) $data[$id];
                    break;
                case 'date':
                    $data[$id] = Carbon::parse($data[$id])->format('Y-m-d');
                    break;
                case 'time':
                    $data[$id] = Carbon::parse($data[$id])->format('H:i:s');
                    break;
                case 'datetime-local':
                    $data[$id] = Carbon::parse($data[$id])->format('Y-m-d H:i:s');
                    break;
            }
        }

        return $data;
    }

    /**
     * Compile the form.
     *
     * @param  \Kris\LaravelFormBuilder\Form  $form
     * @param  array  $fields_data
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function form(Form $form, array $fields_data)
    {
        $fields = $this->fields($fields_data);

        foreach ($fields as $field) {
            $id = $field['id'];
            unset($field['id']);

            $type = $field['type'];
            unset($field['type']);

            $form->add($id, $type, $field);
        }

        return $form;
    }

    /**
     * Parsing validation rules.
     *
     * @param  array  $fields
     * @return array
     */
    public function rules(array $fields)
    {
        $rules = [];

        foreach ($this->fields($fields) as $field) {
            if (isset($field['back_rules'])) {
                $rules[$field['id']] = $field['back_rules'];
            } elseif (isset($field['rules'])) {
                $rules[$field['id']] = $field['rules'];
            }
        }

        return $rules;
    }
}
