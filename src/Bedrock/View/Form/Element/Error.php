<?php

namespace Peak\Bedrock\View\Form\Element;

use Peak\Bedrock\View\Form\Element;

class Error extends Element
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'attrs' => [
            'class' => 'error',
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
        if (empty($this->data)) {
            return '';
        }
        $attrs = $this->attributes(false);

        return '
            <p '.$attrs.'>
                '.$this->data.'
            </p>';
    }
}
