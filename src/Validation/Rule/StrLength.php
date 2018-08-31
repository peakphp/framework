<?php

namespace Peak\Validation\Rule;

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
    protected $defaultOptios = [
        'min' => 0,
        'max' => null,
    ];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $length = mb_strlen($value);

        if (isset($this->options['min'])) {
            $min = $this->options['min'];
        }
        if (isset($this->options['max'])) {
            $max = $this->options['max'];
        }

        if (isset($min) && !isset($max)) {
            return ($length >= $min);
        } elseif (isset($max) && !isset($min)) {
            return ($length <= $max);
        }
        
        return (($length >= $min) && ($length <= $max));
    }
}
