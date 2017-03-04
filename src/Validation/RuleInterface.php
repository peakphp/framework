<?php

namespace Peak\Validation;

interface RuleInterface
{
    /**
     * Construct
     * 
     * @param array   $options rules options array
     * @param integer $flags   rules flags
     * @param array   $context rules context data
     */
    public function __construct($options = null, $flags = null, $context = null);

    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value);
}