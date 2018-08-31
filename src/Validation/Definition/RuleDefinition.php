<?php

namespace Peak\Validation\Definition;

class RuleDefinition
{
    /**
     * @var string
     */
    protected $ruleName;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var int
     */
    protected $flags;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var array
     */
    protected $context;

    /**
     * RuleDefinition constructor.
     *
     * @param string $ruleName
     * @param $options
     * @param $flags
     * @param $errorMessage
     */
    public function __construct(
        string $ruleName,
        array $options,
        ?int $flags,
        ?array $context,
        string $errorMessage = ''
    ) {
        $this->ruleName = $ruleName;
        $this->options = $options;
        $this->flags = $flags;
        $this->errorMessage = $errorMessage;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->ruleName;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return int
     */
    public function getFlags(): int
    {
        return $this->flags;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
