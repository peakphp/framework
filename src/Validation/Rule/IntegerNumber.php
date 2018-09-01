<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class IntegerNumber
 * @package Peak\Validation\Rule
 */
class IntegerNumber extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [
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
