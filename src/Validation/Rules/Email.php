<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Email rule using FILTER_VALIDATE_EMAIL
 */
class Email extends AbstractRule
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
        if (filter_var($value, FILTER_VALIDATE_EMAIL, $options) !== false) {
            return true;
        }
        return false;
    }
}
