<?php

namespace Peak\Bedrock\View\Form\Control;

use Peak\Bedrock\View\Form\FormControl;

/**
 * Textarea control helper
 */
class Textarea extends FormControl
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
            'placeholder' => '',
            'row'         => 3,
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
        $attrs = $this->attributes(false);
        return '<textarea '.$attrs.'>'.$this->data.'</textarea>';
    }
}
