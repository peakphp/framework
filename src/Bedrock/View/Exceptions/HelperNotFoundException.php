<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Exceptions;

/**
 * Class HelperNotFoundException
 * @package Peak\Bedrock\View\Exceptions
 */
class HelperNotFoundException extends \Exception
{
    /**
     * HelperNotFoundException constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct('View helper "'.$name.'"" not found');
    }
}
