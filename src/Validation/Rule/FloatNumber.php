<?php

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Float rule using FILTER_VALIDATE_FLOAT
 */
class FloatNumber extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptios = [
        'decimal' => '.',
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
        if (filter_var($value, FILTER_VALIDATE_FLOAT, $options) !== false) {
            return true;
        }
        return false;
    }
}
