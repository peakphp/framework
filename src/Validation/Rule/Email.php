<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class Email
 * @package Peak\Validation\Rule
 */
class Email extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [];

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
