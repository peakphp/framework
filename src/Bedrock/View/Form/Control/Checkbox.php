<?php

namespace Peak\Bedrock\View\Form\Control;

use Peak\Bedrock\View\Form\FormControl;

/**
 * Checkbox
 */
class Checkbox extends FormControl
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'label'       => '',
        'description' => '',  
        'attrs' => [
            'class'       => '',
            'required'    => false,
            'type'        => 'checkbox',
        ],
    ];

    /**
     * Do stuff after constructor
     */
    public function init() {}

    /**
     * Generated the control
     *
     * @return string
     */
    public function generate()
    {
        $attrs = $this->attributes();
        return '<input '.$attrs.'>';
    }
}
