<?php

declare(strict_types=1);

namespace Peak\Di\Binding;

abstract class AbstractBinding implements BindingInterface
{
    /**
     * Types available
     */
    const SINGLETON = 1;
    const PROTOTYPE = 2;
    const FACTORY = 3;

    protected string $name;

    protected int $type;

    protected $definition;

    /**
     * Constructor
     * @param string $name
     * @param int $type
     * @param mixed $definition
     */
    public function __construct(string $name, int $type, $definition)
    {
        $this->name = $name;
        $this->type = $type;
        $this->definition = $definition;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Get definition
     *
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
