<?php

namespace Peak\View\Form\Element;

use Peak\View\Form\Element;

class Label extends Element
{
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

        return '<label '.$attrs.'>'.__($this->data).'</label>';
    }
}
