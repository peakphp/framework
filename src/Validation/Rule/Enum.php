<?php

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Enum match rule
 */
class Enum extends AbstractRule
{
    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return in_array($value, $this->options);
    }
}
