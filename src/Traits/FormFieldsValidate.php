<?php

namespace Ycs77\LaravelFormFieldType\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Ycs77\LaravelFormFieldType\Facades\FieldType;

trait FormFieldsValidate
{
    /**
     * Get form field array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null $fields
     * @return array
     */
    protected function getFormFields(array $fields = null)
    {
        if ($fields) {
            $result = $fields;
        } elseif (method_exists($this, 'fields')) {
            $result = $this->fields();
        } elseif (property_exists($this, 'formFields')) {
            $result = $this->formFields->fields();
        }

        return $result;
    }

    /**
     * Validate request form data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null $fields
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateFormData(Request $request, array $fields = null)
    {
        $fields = $this->getFormFields();
        $custom_messages = property_exists($this, 'validateMessage') ? $this->validateMessage : [];

        $request->validate(FieldType::rules($fields), $custom_messages);
    }

    /**
     * Get request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null $fields
     * @return array
     */
    protected function getRequestData(Request $request, array $fields = null)
    {
        $fields = $this->getFormFields();
        return $request->only(FieldType::list($fields));
    }

    /**
     * Return validation error.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedResponse()
    {
        $custom_messages = property_exists($this, 'failedMessage') ? $this->failedMessage : [];

        throw ValidationException::withMessages($custom_messages);
    }
}
