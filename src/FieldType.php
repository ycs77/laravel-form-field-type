<?php

namespace Ycs77\LaravelFormFieldType;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Traits\Macroable;
use Kris\LaravelFormBuilder\Form;

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
     * Create new instance
     */
    public function __construct($app)
    {
        $this->config = $app['config']->get('field');
        $this->formConfig = $app['config']->get('laravel-form-builder');
    }

    /**
     * Return the complete type of the specified type of data.
     *
     * @param  string $name
     * @param  array $data
     * @return array
     */
    public function type(string $name, array $data = [])
    {
        $default = [];

        if (isset($this->config['types'][$data['type'] ?? null])) {
            $default = $this->config['types'][$data['type']];
            unset($data['type']);
        } elseif (isset($this->config['types'][$name])) {
            $default = array_merge(
                $default,
                $this->config['types'][$name]
            );
        }

        return array_merge(
            $default,
            ['id' => $name],
            $data
        );
    }

    /**
     * Parsing field data.
     *
     * @param  array $fields
     * @return array
     */
    public function fields(array $fields)
    {
        $result = [];

        foreach ($fields as $key => $value) {
            if (is_string($value)) {
                $result[] = $this->type($value);
            } elseif (is_array($value)) {
                $result[] = $this->type($key, $value);
            }
        }

        return $result;
    }

    /**
     * Return the ID of each field.
     *
     * @param  array $fields
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
     * @param  array $fields
     * @param  Closure $collback
     * @return array
     */
    public function map(array $fields, Closure $collback)
    {
        return array_map($collback, $this->fields($fields));
    }

    /**
     * Transform to the right type.
     *
     * @param  array $fields
     * @param  array $data
     * @return array
     */
    public function casts(array $fields, array $data)
    {
        foreach ($this->fields($fields) as $field) {
            $id = $field['id'];
            switch ($field['type']) {
                case 'number':
                case 'range':
                    $data[$id] = (int)$data[$id];
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
     * Compile the form field.
     *
     * @param  \Kris\LaravelFormBuilder\Form $form
     * @param  array $fields_data
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function render(Form $form, array $fields_data)
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
     * @param  array $fields
     * @return array
     */
    public function rules(array $fields)
    {
        $rules = [];

        foreach ($this->fields($fields) as $field) {
            if (isset($field['rules'])) {
                $rules[$field['id']] = $field['rules'];
            }
        }

        return $rules;
    }
}
