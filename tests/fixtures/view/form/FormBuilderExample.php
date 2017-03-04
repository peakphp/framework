<?php

use Peak\View\Form\FormBuilder;

class FormBuilderExample extends FormBuilder
{
    protected $items = [

        'id' => [
            'type' => 'input',
            'settings' => [
                'attrs'   => [
                    'type' => 'hidden',
                ],
            ],
            'validation' => [
                'if_not_empty', 
                [
                    'rule'  => 'IntegerNumber',
                    'error' => 'Id is not valid',
                ]
            ]
        ],

        'user' => [

            'type' => 'input',
            'settings' => [
                'label' => 'Name',
                'attrs' => [
                    'placeholder' => 'name or address email',
                    'autofocus'   => 'autofocus',
                    'required'    => true,
                ],
            ],
        ],


        'password' => [
            'type' => 'input',
        ],
    ];

}