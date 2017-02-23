<?php

namespace Peak\Validation;

interface RuleInterface
{
    /**
     * Construct
     * 
     * @param array $options merge options with default options
     */
    public function __construct($options = []);

    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value);
}