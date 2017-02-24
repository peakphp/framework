<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Float rule using FILTER_VALIDATE_FLOAT
 */
class Float extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'decimal' => '.',
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
        if(filter_var($value, FILTER_VALIDATE_FLOAT, $options) !== false) {
            return true;
        }
        return false;
    }
}
