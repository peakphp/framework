<?php
namespace Peak\View\Form\Control;

use Peak\View\Form\FormControl;

/**
 * Input control helper
 */
class Input extends FormControl
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
            'spellcheck'  => 'true',
            'type'        => 'text',
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
        $this->options['attrs']['ref'] = $this->name;
        $attrs = $this->attributes();
        return '<input '.$attrs.'>';
    }
}