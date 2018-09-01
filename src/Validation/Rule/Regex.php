<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class Regex
 * @package Peak\Validation\Rule
 */
class Regex extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [
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
