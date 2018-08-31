<?php

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Url rule using FILTER_VALIDATE_URL
 */
class Url extends AbstractRule
{
    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $options = $this->getFilterVarOptions();
        if (filter_var($value, FILTER_VALIDATE_URL, $options) !== false) {
            return true;
        }
        return false;
    }
}
