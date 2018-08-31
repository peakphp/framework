<?php

namespace Peak\Validation\Rule;

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
    public function validate($value): bool
    {
        return !empty($value);
    }
}
