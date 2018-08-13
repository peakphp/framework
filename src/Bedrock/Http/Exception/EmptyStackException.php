<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Exception;

use Peak\Bedrock\Http\StackInterface;

/**
 * Class EmptyStackException
 * @package Peak\Bedrock\Http\Exception
 */
class EmptyStackException extends \InvalidArgumentException
{
    /**
     * @var StackInterface
     */
    private $stack;

    /**
     * StackEndedWithoutResponseException constructor.
     *
     * @param StackInterface $stack
     */
    public function __construct(StackInterface $stack)
    {
        parent::__construct('Stack handlers cannot be empty');
        $this->stack = $stack;
    }

    /**
     * @return StackInterface
     */
    public function getStack()
    {
        return $this->stack;
    }
}
