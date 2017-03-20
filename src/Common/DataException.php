<?php

namespace Peak\Common;

/**
 * Peak commom data exception
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
    public function __construct($message, $data = null)
    {
        parent::__construct($message);
        $this->data = $data;
    }

    /**
     * Var dump the exception data
     *
     * @return string
     */
    public function dumpData()
    {
        ob_start();
        var_dump($this->data);
        return ob_get_clean();
    }
}
