<?php

namespace Peak\Validation\Rules;

use Peak\Validation\AbstractRule;

/**
 * Regex rule using FILTER_VALIDATE_REGEXP
 */
class Regex extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [
        'regexp' => '',
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
        if(filter_var($value, FILTER_VALIDATE_REGEXP, $options) !== false) {
            return true;
        }
        return false;
    }
}
