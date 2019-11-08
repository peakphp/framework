<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

class InvalidPropertyDefinitionException extends \Exception
{
    /**
     * @var object
     */
    private $class;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * InvalidPropertyDefinitionException constructor.
     * @param object $class
     * @param string $propertyName
     */
    public function __construct(object $class, string $propertyName)
    {
        $this->class = get_class($class);
        $this->propertyName = $propertyName;
        parent::__construct(getClassShortName($this->class).' - Structure definition for [' . $propertyName . '] must be an instance of DataType');
    }

    /**
     * @return object
     */
    public function getClass(): object
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
}
