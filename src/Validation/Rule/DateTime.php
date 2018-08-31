<?php

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * DateTime rule
 */
class DateTime extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptios = [
        'format' => 'Y-m-d H:i:s'
    ];

    /**
     * Validate
     *
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        $date = \DateTime::createFromFormat($this->options['format'], $value);
        return $date && $date->format($this->options['format']) == $value;
    }
}
