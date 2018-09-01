<?php

declare(strict_types=1);

namespace Peak\Validation\Rule;

use Peak\Validation\AbstractRule;

/**
 * Class DateTime
 * @package Peak\Validation\Rule
 */
class DateTime extends AbstractRule
{
    /**
     * Default options
     * @var array
     */
    protected $defaultOptions = [
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
