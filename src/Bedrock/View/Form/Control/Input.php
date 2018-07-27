<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Form\Control;

use Peak\Bedrock\View\Form\FormControl;

/**
 * Class Input
 * @package Peak\Bedrock\View\Form\Control
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
    public function init()
    {
    }

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
