<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Request\Exception;

/**
 * Class UnresolvableHandlerException
 * @package Peak\Bedrock\Http\Request\Exception
 */
class UnresolvableHandlerException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $handler;

    /**
     * UnresolvableHandlerException constructor.
     *
     * @param $handler
     */
    public function __construct($handler)
    {
        parent::__construct('Cannot resolve handler');
        $this->handler = $handler;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }
}
