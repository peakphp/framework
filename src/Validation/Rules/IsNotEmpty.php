<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Value is not empty
 */
class IsNotEmpty extends AbstractRule
{
    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        return !empty($value);
    }
}
