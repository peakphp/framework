<?php

namespace Peak\Validation\Rule;

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
    protected $defaultOptios = [
        'regexp' => '',
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
        if (filter_var($value, FILTER_VALIDATE_REGEXP, $options) !== false) {
            return true;
        }
        return false;
    }
}
