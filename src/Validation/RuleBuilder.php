<?php

declare(strict_types=1);

namespace Peak\Validation;

use Peak\Blueprint\Common\Validator;
use Peak\Validation\Exception\NotFoundException;
use \Exception;

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
     * @var mixed
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
        $this->ruleName = $ruleName;
    }

    /**
     * Set rule options
     *
     * @param  array $opts
     * @return $this
     */
    public function setOptions(array $opts)
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
    public function setErrorMessage(string $error)
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
    public function setFlags(?int $flags)
    {
        $this->flags = $flags;
        return $this;
    }

    /**
     * Set context
     *
     * @param  mixed $context
     * @return $this
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return Validator
     * @throws NotFoundException
     */
    public function build(): Validator
    {
        $ruleName = $this->ruleName;
        if (!class_exists($ruleName)) {
            $ruleName = '\Peak\Validation\Rule\\'.$this->ruleName;
        }

        if (!class_exists($ruleName)) {
            throw new NotFoundException($this->ruleName);
        }

        return new $ruleName($this->options, $this->flags, $this->context);
    }
}
