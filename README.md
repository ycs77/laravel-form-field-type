# Laravel form field type

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-circleci]][link-circleci]
[![Total Downloads][ico-downloads]][link-downloads]

Fast set the form fields of the [Laravel form builder](https://github.com/kristijanhusak/laravel-form-builder).

## Install

[Laravel form builder](https://github.com/kristijanhusak/laravel-form-builder) must be installed.

Via Composer

```bash
composer require ycs77/laravel-form-field-type
```

Publish config

```bash
php artisan vendor:publish --tag=laravel-form-field-type-config
```

Suggestions can be matched with [Laravel form builder BS4](https://github.com/ycs77/laravel-form-builder-bs4)

## Usage

The commonly used fields can be defined in `config/field.php` and the FieldType will be loaded automatically.

> In this case, the 'name' and 'phone' fields have been defined in config/field.php, so they can be used directly.

```php

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Ycs77\LaravelFormFieldType\Facades\FieldType;

class MyController extends Controller 
{
    use FormBuilderTrait;

    protected $fields = [
        'name',
        'phone',
        'meeting_time' => [
            'type'  => 'datetime-local',
            'rules' => 'required',
        ],
    ];

    public function index()
    {
        // Parsing form fields.
        $form = FieldType::render($this->plain([
            'url'    => '/url',
            'method' => 'POST',
        ]), $this->fields);

        ...
    }

    public function store(Request $request)
    {
        // Get validation rules.
        $rules = FieldType::rules($this->fields);

        // Verification.
        $request->validate($rules);

        // Get the information.
        $requestData = $request->only(
            FieldType::list($this->fields)
        );
        // Transform to the right type.
        $data = FieldType::casts($this->fields, $requestData);

        ...
    }
}

```

## Methods

### type

If you enter a field defined by `config/field.php`, the field will be returned.

Return the complete type of the specified type of data:

Get field:
```php
$array = FieldType::type('age', [
    'type' => 'number',
]);

// [
//     'id'   => 'age',
//     'type' => 'number',
// ]
```

Get exist field type:
```php
$array_2 = FieldType::type('nickname', [
    'type' => 'name',
    'rules' => 'required',
]);

// [
//     'id'    => 'nickname',
//     'type'  => 'text',
//     'rules' => 'required',
// ]
```

Override field type:
```php
$array_3 = FieldType::type('name', [
    'rules' => 'required',
]);

// [
//     'id'    => 'name',
//     'type'  => 'text',
//     'rules' => 'required',
// ]
```

If use `front_rules` attribute, only front use this rules:
```php
$array_front_rules = FieldType::type('name', [
    'type' => 'name',
    'front_rules' => 'required',
]);

// [
//     'id'    => 'name',
//     'type'  => 'text',
//     'rules' => 'required',
// ]
```

### fields

The `fields` method is to traverse the array to execute the `field` method.

Parsing field data:

```php
$fields = [
    'name',
    'age' => [
        'type' => 'number',
    ],
];
$array = FieldType::fields($fields);

// [
//     [
//         'id'    => 'name',
//         'type'  => 'text',
//         'rules' => 'required|max:20',
//     ],
//     [
//         'id'   => 'age',
//         'type' => 'number',
//     ],
// ]
```

### list

Return the ID of each field:

```php
$fields = [
    'name',
    'age' => [
        'type' => 'number',
    ],
];
$array = FieldType::list($fields);

// ['name', 'age']
```

### casts

Transform to the right type:

```php
$fields = [
    'name',
    'meeting_time' => [
        'type'  => 'datetime-local',
        'rules' => 'required',
    ],
];
$data = [
    'name'         => 'Bob',
    'meeting_time' => '2018-01-01T00:00',
];
$array = FieldType::casts($fields, $data);

// [
//     'name'         => 'Bob',
//     'meeting_time' => '2018-01-01 00:00:00',
// ]
```

### render

Compile the form field:

```php
$form = $this->plain();
$fields = [
    'name',
    'meeting_time' => [
        'type' => 'datetime-local',
        'rules' => 'required',
    ],
];
$form = FieldType::render($form, $fields);

// => \Kris\LaravelFormBuilder\Form
// All fields have been added to the form.
```

### rules

If use `back_rules` attribute, only back use this rules.

Parsing validation rules:

```php
$fields = [
    'name',
    'phone' => [
        'rules' => 'required',
    ],
    'age' => [
        'back_rules' => 'required',
    ],
];
$array = FieldType::rules($fields);

// [
//     'name'  => 'required|max:20',
//     'phone' => 'required',
//     'age'   => 'required',
// ]
```


## Use checkable group

Now Laravel form builder has not added the checkable group feature. If you need to use this feature, you need to add it as follows:

```bash
php artisan vendor:publish --tag=laravel-form-checkable-group-type
```

*config/laravel-form-builder.php*
```php
<?php

return [
    ...

    'custom_fields' => [
        'checkable_group' => '\Ycs77\LaravelFormFieldType\Fields\CheckableGroupType',
        ...
    ],
];

```

### Use render form

```php
$form = $this->plain();
$fields = [
    'checkbox_group_field' => [
        'type' => 'checkable_group',
        'choices' => [
            'en' => 'English',
            'fr' => 'French',
        ],
        'choice_options' => [
            'wrapper' => [
                'class' => 'form-control',
            ],
        ],
        'selected' => ['en'],
    ],
];
$form = FieldType::render($form, $fields);

// => \Kris\LaravelFormBuilder\Form
```

### Config

*config/laravel-form-builder.php*
```php
<?php

return [
    'defaults' => [
        ...

        'checkable_group' => [
            'wrapper_class' => 'form-group',
            'label_class'   => '',
        ],

        'checkbox' => [
            ...

            'choice_options' => [
                'wrapper_class' => 'custom-control custom-checkbox',
                'label_class' => 'custom-control-label',
                'field_class' => 'custom-control-input',
            ],
        ],

        'radio' => [
            ...

            'choice_options' => [
                'wrapper_class' => 'custom-control custom-radio',
                'label_class' => 'custom-control-label',
                'field_class' => 'custom-control-input',
            ],
        ],
    ],
];

```


[ico-version]: https://img.shields.io/packagist/v/ycs77/laravel-form-field-type.svg?style=flat
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
[ico-circleci]: https://img.shields.io/circleci/project/github/ycs77/laravel-form-field-type/master.svg?style=flat
[ico-downloads]: https://img.shields.io/packagist/dt/ycs77/laravel-form-field-type.svg?style=flat

[link-packagist]: https://packagist.org/packages/ycs77/laravel-form-field-type
[link-circleci]: https://circleci.com/gh/ycs77/laravel-form-field-type
[link-downloads]: https://packagist.org/packages/ycs77/laravel-form-field-type
