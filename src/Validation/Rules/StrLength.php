<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Value min and max length
 */
class StrLength extends AbstractRule
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
        $length = mb_strlen($value);

        if($this->options['min'] !== null) {
            $min = $this->options['min'];
        }
        if($this->options['max'] !== null) {
            $max = $this->options['max'];
        }

        if(isset($min) && !isset($max)) {
            return ($length >= $min);
        }
        elseif(isset($max) && !isset($min)) {
            return ($length <= $max);
        }
        else {
            return (($length >= $min) && ($length <= $max));
        }
    }
}
