<?php

namespace Ycs77\LaravelFormFieldType;

abstract class FormFields
{
    /**
     * Form data.
     *
     * @var array|null
     */
    protected $data;

    /**
     * Create form fields instance.
     *
     * @param array|null  $data
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * Return form fields array.
     *
     * @return array
     */
    abstract public function fields();
}
