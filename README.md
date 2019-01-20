# Laravel form field type

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
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
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

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
$array_2 = FieldType::type('name');

// [
//     'id'    => 'name',
//     'type'  => 'text',
//     'rules' => 'required|max:20',
// ]
```

Override field type:
```php
$array_3 = FieldType::type('nickname', [
    'type' => 'name',
    'rules' => 'required',
]);

// [
//     'id'    => 'nickname',
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

Parsing validation rules:

```php
$fields = [
    'name',
    'age' => [
        'type'  => 'number',
        'rules' => 'required',
    ],
];
$array = FieldType::rules($fields);

// [
//     'name' => 'required|max:20',
//     'age'  => 'required',
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
        'checkable_group' => '\App\Forms\Fields\CheckableGroupType',
        ...
    ],
];

```

### Use render form

```php
$form = $this->plain();
$fields = [
    'checkbox_group_field' => [
        'type' => 'checkbox_group',
        'options' => [
            'checkbox_1' => 'Checkbox 1',
            'checkbox_2' => 'Checkbox 2',
            'checkbox_3' => 'Checkbox 3',
        ],
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
            ...

            'checkbox' => [
                'wrapper_class' => 'custom-control custom-checkbox',
            ],
            'radio' => [
                'wrapper_class' => 'custom-control custom-radio',
            ],
        ],
    ],
];

```


[ico-version]: https://img.shields.io/packagist/v/ycs77/laravel-form-field-type.svg?style=flat
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat
[ico-travis]: https://img.shields.io/travis/ycs77/laravel-form-field-type/master.svg?style=flat
[ico-downloads]: https://img.shields.io/packagist/dt/ycs77/laravel-form-field-type.svg?style=flat

[link-packagist]: https://packagist.org/packages/ycs77/laravel-form-field-type
[link-travis]: https://travis-ci.org/ycs77/laravel-form-field-type
[link-downloads]: https://packagist.org/packages/ycs77/laravel-form-field-type
[link-author]: https://github.com/ycs77
[link-contributors]: ../../contributors
