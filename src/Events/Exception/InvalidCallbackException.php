<?php

declare(strict_types=1);

namespace Peak\Events\Exception;

/**
 * Class InvalidCallbackException
 * @package Peak\Config\Exception
 */
class InvalidCallbackException extends \Exception
{
    /**
     * InvalidCallbackException constructor.
     * @param string $name
     * @param int $index
     */
    public function __construct(string $name, int $index)
    {
        parent::__construct('Callback #'.$index.' for event "'.$name.'" is invalid. Only Closure, Classname or Object instance implementing EventInterface are allowed.');
    }
}
