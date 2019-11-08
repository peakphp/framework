<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

class UndefinedPropertyException extends \Exception
{
    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $class;

    /**
     * UndefinedPropertyException constructor.
     * @param object $class
     * @param string $propertyName
     */
    public function __construct(object $class, string $propertyName)
    {
        $this->class = get_class($class);
        $this->propertyName = $propertyName;
        parent::__construct(getClassShortName($this->class).' - Property [' . $propertyName . '] is undefined in the structure');
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
