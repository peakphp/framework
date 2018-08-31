<?php

namespace Peak\Validation\Rule;

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
    protected $defaultOptios = [
        'min_range' => null,
        'max_range' => null,
    ];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $options = $this->getFilterVarOptions();
        if (filter_var($value, FILTER_VALIDATE_INT, $options) !== false) {
            return true;
        }
        return false;
    }
}
