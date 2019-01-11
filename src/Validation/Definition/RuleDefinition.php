<?php

declare(strict_types=1);

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
     * @var int|null
     */
    protected $flags;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var array|null
     */
    protected $context;

    /**
     * RuleDefinition constructor.
     * @param string $ruleName
     * @param array $options
     * @param int|null $flags
     * @param array|null $context
     * @param string $errorMessage
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
     * @return int|null
     */
    public function getFlags(): ?int
    {
        return $this->flags;
    }

    /**
     * @return array|null
     */
    public function getContext(): ?array
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
