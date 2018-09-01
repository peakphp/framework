<?php

declare(strict_types=1);

namespace Peak\Validation\Exception;

use \Exception;

/**
 * Class NotFoundException
 * @package Peak\Validation\Exception
 */
class NotFoundException extends Exception
{
    /**
     * NotFoundException constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct('Rule "'.$name.'" not found');
    }
}
