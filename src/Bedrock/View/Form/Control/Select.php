<?php

namespace Peak\Bedrock\View\Form\Control;

use Peak\Bedrock\View\Form\FormControl;

/**
 * Select
 */
class Select extends FormControl
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'label'             => '',
        'description'       => '',
        'options'           => [],
        'default'           => '',
        'value_as_key'      => false,
        'attrs'             => [
            'class'       => '',
            'required'    => false,
            'placeholder' => '',
            'multiple'    => false,
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
        $attrs = $this->attributes(false);

        $control = '<select '.$attrs.'>';

        $data = (trim($this->data) === '') ? $this->options['default'] : $this->data;

        if (array_key_exists('options', $this->options) && !empty($this->options['options'])) {
            foreach ($this->options['options'] as $k => $v) {
                if ($this->options['value_as_key']) {
                    $k = $v;
                }
                $control .= '<option '.(($k == $data) ? 'selected' : '').' value="'.$k.'">'.$v.'</option>';
            }
        }
        
        $control .= '</select>';

        return $control;
    }
}
