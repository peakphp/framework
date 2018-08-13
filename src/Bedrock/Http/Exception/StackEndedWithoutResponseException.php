<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Exception;

use Peak\Bedrock\Http\StackInterface;

/**
 * Class StackEndedWithoutResponseException
 * @package Peak\Bedrock\Http\Exception
 */
class StackEndedWithoutResponseException extends \LogicException
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
        parent::__construct('Stack ended without returning a response');
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
