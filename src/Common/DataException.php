<?php

declare(strict_types=1);

namespace Peak\Common;

/**
 * Data exception
 */
class DataException extends \Exception
{
    /**
     * Data that trigger this exception if any
     * @var mixed
     */
    protected $data = null;

    /**
     * Set error key constant
     *
     * @param string $message
     * @param mixed  $data
     */
    public function __construct(string $message, $data = null)
    {
        parent::__construct($message);
        $this->data = $data;
    }

    /**
     * Var dump the exception data
     *
     * @return string
     */
    public function dumpData(): string
    {
        ob_start();
        var_dump($this->data);
        return ob_get_clean();
    }
}
