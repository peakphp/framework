<?php

namespace Peak\Validation\Rule;

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
    protected $defaultOptios = [];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $options = $this->getFilterVarOptions();
        if (filter_var($value, FILTER_VALIDATE_EMAIL, $options) !== false) {
            return true;
        }
        return false;
    }
}
