<?php

declare(strict_types=1);

namespace Peak\Http\Request\Exception;

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
