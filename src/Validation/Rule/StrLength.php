<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class StrLength
 * @package Peak\Validation\Rule
 */
class StrLength extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [
        'min' => 0,
        'max' => null,
    ];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $length = mb_strlen((string)$value);

        if (isset($this->options['min'])) {
            $min = $this->options['min'];
        }
        if (isset($this->options['max'])) {
            $max = $this->options['max'];
        }

        if (isset($min) && !isset($max)) {
            return ($length >= $min);
        } elseif (isset($max) && !isset($min)) {
            return ($length <= $max);
        }
        
        return (($length >= $min) && ($length <= $max));
    }
}
