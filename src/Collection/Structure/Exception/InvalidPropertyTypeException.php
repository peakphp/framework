<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

use function get_class;
use function implode;

class InvalidPropertyTypeException extends \Exception
{
    private string $class;

    private string $propertyName;

    private array $typesExpected;

    private string $typeReceived;

    /**
     * InvalidPropertyTypeException constructor.
     * @param object $class
     * @param string $propertyName
     * @param array $typesExpected
     * @param string $typeReceived
     */
    public function __construct(object $class, string $propertyName, array $typesExpected, string $typeReceived)
    {
        $this->class = get_class($class);
        $this->propertyName = $propertyName;
        $this->typesExpected = $typesExpected;
        $this->typeReceived = $typeReceived;
        $typesExpectedString = implode(' OR ', $typesExpected);
        parent::__construct(
            getClassShortName($this->class).' - Property ['.$propertyName.'] expect a type of ('.$typesExpectedString.') ... '. $typeReceived . ' given'
        );
    }

    /**
     * @return string
     */
    public function getClass(): string
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

    /**
     * @return array
     */
    public function getTypesExpected(): array
    {
        return $this->typesExpected;
    }

    /**
     * @return string
     */
    public function getTypeReceived(): string
    {
        return $this->typeReceived;
    }
}
