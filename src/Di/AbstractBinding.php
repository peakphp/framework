<?php

declare(strict_types=1);

namespace Peak\Di;

/**
 * Class AbstractBinding
 * @package Peak\Di
 */
abstract class AbstractBinding implements BindingInterface
{
    /**
     * Type available
     */
    const SINGLETON = 1;
    const PROTOTYPE = 2;
    const FACTORY = 3;

    /**
     * Binding name
     * @var string
     */
    protected $name;

    /**
     * Binding type
     * @var integer
     */
    protected $type;

    /**
     * Binding definition
     * @var mixed
     */
    protected $definition;

    /**
     * Constructor
     * @param string $name
     * @param integer $type
     * @param mixed $definition
     */
    public function __construct($name, $type, $definition)
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get definition
     *
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}
