<?php

declare(strict_types=1);

namespace Peak\Http\Exception;

use Peak\Blueprint\Http\Stack;

/**
 * Class EmptyStackException
 * @package Peak\Http\Exception
 */
class EmptyStackException extends \InvalidArgumentException
{
    /**
     * @var Stack
     */
    private $stack;

    /**
     * StackEndedWithoutResponseException constructor.
     *
     * @param Stack $stack
     */
    public function __construct(Stack $stack)
    {
        parent::__construct('Stack handlers cannot be empty');
        $this->stack = $stack;
    }

    /**
     * @return Stack
     */
    public function getStack()
    {
        return $this->stack;
    }
}
