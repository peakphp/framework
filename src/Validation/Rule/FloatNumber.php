<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class FloatNumber
 * @package Peak\Validation\Rule
 */
class FloatNumber extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [
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
