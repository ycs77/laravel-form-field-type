<?php

namespace Ycs77\LaravelFormFieldType;

abstract class FormFields
{
    /**
     * Form data.
     *
     * @var array
     */
    protected $data;

    /**
     * Create form fields instance.
     *
     * @param  array  $data
     */
    public function __construct($data = [])
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
