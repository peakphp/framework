<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Value min and max length
 */
class Length extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'min' => 0,
        'max' => null,
    ];

    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        if(!is_null($this->options['min'])) {
            $min = $this->options['min'];
        }
        if(!is_null($this->options['min'])) {
            $max = $this->options['max'];
        }

        if(isset($min) && !isset($max)) {
            return (strlen($value) >= $min) ? true : false;
        }
        elseif(isset($max) && !isset($min)) {
            return (strlen($value) <= $max) ? true : false;
        }
        else {
            return ((strlen($value) >= $min) && (strlen($value) <= $max)) ? true : false;
        }
    }
}
