<?php

namespace Peak\Validation;

use Peak\Blueprint\Common\Validator;
use \Exception;

/**
 * Rule builder
 */
class RuleBuilder
{
    /**
     * Rule class name
     * @var string
     */
    protected $ruleName;

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
     * Rule context data
     * @var array
     */
    protected $context;

    /**
     * Validation error message
     * @var string
     */
    protected $error;

    /**
     * Constructor
     *
     * @param string $ruleName rule class name
     */
    public function __construct(string $ruleName)
    {
        $this->name = $ruleName;
    }

    /**
     * Set rule options
     *
     * @param  array $opts
     * @return $this
     */
    public function setOptions(?array $opts)
    {
        $this->options = $opts;
        return $this;
    }

    /**
     * Set error
     *
     * @param  string $error
     * @return $this
     */
    public function setErrorMessage($error)
    {
        $this->error = $error;
        return $this;
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
     * Set context
     *
     * @param  array $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function build(): Validator
    {
        $ruleName = $this->ruleName;
        if (!class_exists($this->ruleName)) {
            $ruleName = '\Peak\Validation\Rules\\'.$this->ruleName;
        }

        if (!class_exists($ruleName)) {
            throw new Exception('Rule "'.$this->ruleName.'" not found');
        }

        return new $ruleName($this->options, $this->flags, $this->context);
    }
}
