<?php

namespace Peak\Doorman;

use Peak\Doorman\PermissionFormats\FormatAlphaNum;
use Peak\Doorman\PermissionFormats\FormatBinary;
use Peak\Doorman\PermissionFormats\FormatText;
use Peak\Doorman\PermissionFormats\FormatDecimalString;
use Peak\Doorman\PermissionFormats\FormatChar;

/**
 * Permissions resolver (support decimal, binary, text and alphanum representation)
 */
class PermissionResolver
{
    /**
     * The permission raw format
     * @var mixed
     */
    public $raw;

    /**
     * The permission resolve in decimal format
     * @var integer
     */
    public $permission;

    /**
     * Construtor
     *
     * @param mixed $permission
     */
    public function __construct($permission)
    {
        $this->raw = $permission;
        $this->resolve();
    }

    /**
     * Try to resolve permission format to decimal value
     */
    public function resolve()
    {
        $this->permission = null;

        // numeric or decimal inside a string
        if (array_key_exists($this->raw, FormatDecimalString::$values)) {
            $this->permission = FormatDecimalString::$values[$this->raw];
        } elseif (array_key_exists($this->raw, FormatAlphaNum::$values)) {
            // alphanum
            $this->permission = FormatAlphaNum::$values[$this->raw];
        } elseif (array_key_exists($this->raw, FormatBinary::$values)) {
            // binary
            $this->permission = FormatBinary::$values[$this->raw];
        } elseif (array_key_exists($this->raw, FormatText::$values)) {
            // textual format 1
            $this->permission = FormatText::$values[$this->raw];
        } elseif (array_key_exists($this->raw, FormatChar::$values)) {
            // textual format 2
            $this->permission = FormatChar::$values[$this->raw];
        }

        // Can't resolve permission format
        if ($this->permission === null) {
            throw new \Exception(__CLASS__.': Invalid permission format');
        }
    }

    /**
     * Get the resolved permission decimal value
     *
     * @return integer
     */
    public function get()
    {
        return $this->permission;
    }
}
