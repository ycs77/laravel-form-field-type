<?php

namespace Ycs77\LaravelFormFieldType\Fields;

use Kris\LaravelFormBuilder\Fields\ParentType;

class CheckableGroupType extends ParentType
{
    /**
     * @var string
     */
    protected $checkableType = 'checkbox';

    /**
     * @inheritdoc
     */
    protected $valueProperty = 'selected';

    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'laravel-form-builder::checkable_group';
    }

    /**
     * Determine which checkable group type to use.
     *
     * @return string
     */
    protected function determineCheckableField()
    {
        $is_checkbox = $this->options['is_checkbox'];

        if (!$is_checkbox) {
            $this->checkableType = 'radio';
        }

        if ($is_checkbox) {
            $this->checkableType = 'checkbox';
        }

        return $this->checkableType;
    }

    /**
     * @inheritdoc
     */
    protected function getDefaults()
    {
        return [
            'choices' => null,
            'selected' => null,
            'is_checkbox' => true,
            'choice_options' => [
                'wrapper' => false,
            ],
        ];
    }

    /**
     * Create children depending on checkable group type.
     *
     * @return void
     */
    protected function createChildren()
    {
        if (($data_override = $this->getOption('data_override')) && $data_override instanceof \Closure) {
            $this->options['choices'] = $data_override($this->options['choices'], $this);
        }

        $this->children = [];
        $this->determineCheckableField();

        $fieldType = $this->formHelper->getFieldType($this->checkableType);

        $this->buildCheckableChildren($fieldType);
    }

    /**
     * Build checkable children fields from checkable group type.
     *
     * @param string $fieldType
     * @return void
     */
    protected function buildCheckableChildren($fieldType)
    {
        $is_checkbox = $this->getOption('is_checkbox') ? '[]' : '';

        foreach ((array)$this->options['choices'] as $key => $choice) {
            if (is_numeric($key) && $choice !== null) {
                $key = $choice;
                $choice = null;
            }

            $id = str_replace('.', '_', $this->getNameKey()) . '_' . $key;
            $options = $this->formHelper->mergeOptions(
                $this->getOption('choice_options'),
                [
                    'attr' => ['id' => $id],
                    'label_attr' => ['for' => $id],
                    'label' => $this->childrenLabel($choice, $key),
                    'checked' => in_array($key, (array)$this->options[$this->valueProperty]),
                    'value' => $key,
                ]
            );

            $this->children[] = new $fieldType(
                $this->name . $is_checkbox,
                $this->checkableType,
                $this->parent,
                $options
            );
        }
    }

    /**
     * Creates default wrapper classes for the form element.
     *
     * @param array $options
     * @return array
     */
    protected function setDefaultClasses(array $options = [])
    {
        $defaults = parent::setDefaultClasses($options);
        $checkable_type = $this->determineCheckableField();

        $wrapper_class = $this->formHelper->getConfig('defaults.' . $this->type . '.' . $checkable_type . '_wrapper_class', '');
        if ($wrapper_class) {
            $defaults['wrapper']['class'] = (isset($defaults['wrapper']['class']) ? $defaults['wrapper']['class'] . ' ' : '') . $wrapper_class;
        }

        $choice_wrapper_class = $this->formHelper->getConfig('defaults.' . $checkable_type . '.choice_options.wrapper_class', '');
        $choice_label_class = $this->formHelper->getConfig('defaults.' . $checkable_type . '.choice_options.label_class', '');
        $choice_field_class = $this->formHelper->getConfig('defaults.' . $checkable_type . '.choice_options.field_class', '');

        if ($choice_wrapper_class) {
            $defaults['choice_options']['wrapper']['class'] = $choice_wrapper_class;
        }
        if ($choice_label_class) {
            $defaults['choice_options']['label_attr']['class'] = $choice_label_class;
        }
        if ($choice_field_class) {
            $defaults['choice_options']['attr']['class'] = $choice_field_class;
        }

        return $defaults;
    }

    /**
     * Return the label for the form field children.
     *
     * @param string $label
     * @param string $name
     * @return string
     */
    protected function childrenLabel($label, $name)
    {
        if ($label !== null) {
            return $label;
        }

        if ($langName = $this->parent->getLanguageName()) {
            $label = sprintf('%s.%s', $langName, $name);
        }

        return $this->formHelper->formatLabel($label);
    }
}
