<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Is not empty rule
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
