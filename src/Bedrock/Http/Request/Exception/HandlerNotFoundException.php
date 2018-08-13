<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Request\Exception;

/**
 * Class HandlerNotFoundException
 * @package Peak\Bedrock\Http\Request\Exception
 */
class HandlerNotFoundException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $handler;

    /**
     * HandlerNotFoundException constructor.
     *
     * @param mixed $handler
     */
    public function __construct($handler)
    {
        parent::__construct('Handler '.$handler.' not found');
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
