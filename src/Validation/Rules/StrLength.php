<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * String length rule
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

        if(isset($this->options['min'])) {
            $min = $this->options['min'];
        }
        if(isset($this->options['max'])) {
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
