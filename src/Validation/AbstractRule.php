<?php

namespace Peak\Validation;

use Peak\Validation\RuleInterface;

abstract class AbstractRule implements RuleInterface
{
    /**
     * Default options
     * @var array
     */
    protected $default_options = [];

    /**
     * Options
     * @var array
     */
    protected $options = [];

    /**
     * Construct
     * 
     * @param array  $options 
     */
    public function __construct($options = [])
    {
        $this->options = array_merge($this->default_options, $options);
        $this->init();
    }

    /**
     * Init after merging options
     */
    public function init()
    {
    }
}
