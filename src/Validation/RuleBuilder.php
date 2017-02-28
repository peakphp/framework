<?php

namespace Peak\Validation;

/**
 * Rule builder
 */
class RuleBuilder
{
    /**
     * Rule class name
     * @var string
     */
    protected $name;

    /**
     * Rule options
     * @var array
     */
    protected $options = [];

    /**
     * Rule flags
     * @var integer
     */
    protected $flags;

    /**
     * Validation error message
     * @var string
     */
    protected $error;

    /**
     * Constructor
     * 
     * @param string $name rule classname
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Set rule options
     * 
     * @param  string $opts
     * @return $this
     */
    public function setOptions($opts)
    {
        $this->options = $opts;
        return $this;
    }

    /**
     * Get options
     * 
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set error
     * 
     * @param  string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Get error
     * 
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set flags
     * 
     * @param  integer $flags
     * @return $this
     */
    public function setFlags($flags)
    {
        $this->flags = $flags;
        return $this;
    }

    /**
     * Get flags
     * 
     * @return integer
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * Create and get a rule
     * 
     * @return object
     */
    public function get()
    {
        $rulename = '\Peak\Validation\Rules\\'.$this->name;

        if(class_exists($this->name)) {
            $rulename = $this->name;
        }

        return new $rulename($this->options, $this->flags);
    }

    /**
     * Validate a value
     * 
     * @param  mixed $value 
     * @return bool       
     */
    public function validate($value)
    {
        return $this->get()->validate($value);
    }
}
