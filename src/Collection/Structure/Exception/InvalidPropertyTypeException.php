<?php

declare(strict_types=1);

namespace Peak\Collection\Structure\Exception;

use function implode;

class InvalidPropertyTypeException extends \Exception
{
    /**
     * InvalidPropertyTypeException constructor.
     * @param string $propertyName
     * @param array $typesExpected
     * @param string $typeReceived
     */
    public function __construct(string $propertyName, array $typesExpected, string $typeReceived)
    {
        $typesExpectedString = implode(' OR ', $typesExpected);
        parent::__construct(
            'Property ['.$propertyName.'] expect a type of ('.$typesExpectedString.') ... '. $typeReceived . ' given'
        );
    }
}
