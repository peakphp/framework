<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class Enum
 * @package Peak\Validation\Rule
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
