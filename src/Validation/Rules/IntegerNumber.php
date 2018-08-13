<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Integer rule using FILTER_VALIDATE_INT
 */
class IntegerNumber extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'min_range' => null,
        'max_range' => null,
    ];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $options = $this->getFilterVarOptions();
        if (filter_var($value, FILTER_VALIDATE_INT, $options) !== false) {
            return true;
        }
        return false;
    }
}
