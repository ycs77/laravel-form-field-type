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
        if (isset($this->config['types'][$name])) {
            $default = $this->config['types'][$name];
        }
        $result = array_merge(
            $default,
            ['id' => $name],
            $data
        );

        return $result;
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
    public function list($fields)
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
    public function map($fields, Closure $collback)
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

            switch ($type) {
                case 'checkbox_group':
                case 'radio_group':
                    $type = $type === 'checkbox_group' ? 'checkbox' : 'radio';
                    $config = $this->formConfig['defaults']['checkable_group'][$type] ?? [];

                    $childForm = $form->getFormBuilder()->plain();
                    foreach ($field['options'] as $option => $data) {
                        if (is_string($data)) {
                            $label = $data;
                            $data = [];
                            $data['label'] = $label;
                            $data['value'] = $label;
                        }
                        if (isset($data['value']) && !isset($data['label'])) {
                            $data['label'] = $data['value'];
                        }
                        $childForm->add($option, $type, array_merge([
                            'wrapper' => [
                                'class' => $config['wrapper_class'] ?? null,
                            ],
                        ], $data));
                    }
                    unset($field['options']);

                    $form->add($id, 'checkable_group', array_merge($field, [
                        'class' => $childForm,
                    ]));
                    break;

                default:
                    $form->add($id, $type, $field);
                    break;
            }
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
            $rules[$field['id']] = $field['rules'];
        }

        return $rules;
    }
}
