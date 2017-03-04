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

        // decimal inside a string
        if(array_key_exists($this->raw, FormatDecimalString::$values)) {
            $this->permission = FormatDecimalString::$values[$this->raw];
        }
        // alphanum
        elseif(array_key_exists($this->raw, FormatAlphaNum::$values)) {
            $this->permission = FormatAlphaNum::$values[$this->raw];
        }
        // binary
        elseif(array_key_exists($this->raw, FormatBinary::$values)) {
            $this->permission = FormatBinary::$values[$this->raw];
        }
        // textual format
        elseif(array_key_exists($this->raw, FormatText::$values)) {
            $this->permission = FormatText::$values[$this->raw];
        }
        // textual format
        elseif(array_key_exists($this->raw, FormatChar::$values)) {
            $this->permission = FormatChar::$values[$this->raw];
        }
        elseif(is_numeric($this->raw) && $this->raw >= 0 && $this->raw <= 7) {
            $this->permission = $this->raw;
        }     

        // Can't resolve permission format
        if($this->permission === null) {
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
