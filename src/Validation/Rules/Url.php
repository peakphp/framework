<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Url rule using FILTER_VALIDATE_URL
 */
class Url extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $options = $this->getFilterVarOptions();
        if (filter_var($value, FILTER_VALIDATE_URL, $options) !== false) {
            return true;
        }
        return false;
    }
}
