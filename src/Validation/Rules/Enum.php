<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Value Emum
 */
class Enum extends AbstractRule
{
    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        return in_array($value, $this->options);
    }
}
