<?php

namespace Ycs77\LaravelFormFieldType\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Ycs77\LaravelFormFieldType\Facades\FieldType;

trait FormFieldsTrait
{
    use FormBuilderTrait;

    /**
     * Get form field array.
     *
     * @param  array|null $fields
     * @return array
     */
    public function getFormFields(array $fields = null)
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
     * Return rendered form instance.
     *
     * @param  array $data
     * @param  array|null $fields
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function renderForm(array $data, array $fields = null)
    {
        $langPath = 'validation.attributes';
        if (property_exists($this, 'langPath')) {
            $langPath = $this->langPath;
        }

        $data = array_merge([
            'language_name' => $langPath,
        ], $data);

        return FieldType::form(
            $this->plain($data),
            $this->getFormFields($fields)
        );
    }

    /**
     * Validate request form data and return.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null $fields
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateFormData(Request $request, array $fields = null)
    {
        $fields = $this->getFormFields($fields);
        $messages = property_exists($this, 'validateMessage') ? $this->validateMessage : [];

        $request->validate(FieldType::rules($fields), $messages);

        return $request->only(FieldType::list($fields));
    }

    /**
     * Return validation error.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedResponse()
    {
        $messages = property_exists($this, 'failedMessage') ? $this->failedMessage : [];
        throw ValidationException::withMessages($messages);
    }
}
