<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Is empty rule
 */
class IsEmpty extends AbstractRule
{
    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        return empty($value);
    }
}
