<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http\Request\Exception;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class InvalidHandlerException
 * @package Peak\Bedrock\Http\Middleware\Exception
 */
class InvalidHandlerException extends \InvalidArgumentException
{
    /**
     * @var mixed
     */
    private $handler;

    /**
     * InvalidHandlerException constructor.
     */
    public function __construct($handler)
    {
        parent::__construct('Invalid server request handler');
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
