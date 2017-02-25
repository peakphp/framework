<?php

namespace Peak\Validation\Rules;

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
    protected $default_options = [
        'format' => 'Y-m-d H:i:s'
    ];

    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value)
    {
        $date = \DateTime::createFromFormat($this->options['format'], $value);
        return $date && $date->format($this->options['format']) == $value;
    }
}
