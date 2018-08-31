<?php

namespace Peak\Validation\Rule;

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
    public function validate($value): bool
    {
        return empty($value);
    }
}
