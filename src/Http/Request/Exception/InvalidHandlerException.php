<?php

declare(strict_types=1);

namespace Peak\Http\Request\Exception;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use function get_class;
use function is_object;
use function is_string;

class InvalidHandlerException extends \InvalidArgumentException
{
    private $handler;

    /**
     * InvalidHandlerException constructor.
     * @param mixed $handler
     */
    public function __construct($handler)
    {
        $msg = 'Invalid server request handler';

        if (is_string($handler)) {
            $msg = '['.$handler.'] is an invalid server request handler';
        } elseif (is_object($handler) &&
            !$handler instanceof ServerRequestInterface &&
            !$handler instanceof MiddlewareInterface
        ) {
            $msg = '['.get_class($handler).'] handler must implements ServerRequestInterface or MiddlewareInterface';
        }
        parent::__construct($msg);
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
