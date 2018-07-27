<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Form\Control;

use Peak\Bedrock\View\Form\FormControl;

/**
 * Class Checkbox
 * @package Peak\Bedrock\View\Form\Control
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
        $attrs = $this->attributes();
        return '<input '.$attrs.'>';
    }
}
