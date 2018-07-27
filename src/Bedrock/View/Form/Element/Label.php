<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Form\Element;

use Peak\Bedrock\View\Form\Element;

/**
 * Class Label
 * @package Peak\Bedrock\View\Form\Element
 */
class Label extends Element
{
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

        return '<label '.$attrs.'>'.$this->data.'</label>';
    }
}
