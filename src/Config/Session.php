<?php

namespace Peak\Config;

use Peak\Exception;
use Peak\Config\DotNotation;

/**
 * Wrap Collection / DotNotation abilities around php $_SESSION
 */
class Session extends DotNotation
{
    /**
     * We pass $_SESSION by reference so we can alter
     * it through the collection
     */
    public function __construct()
    {
        if (isset($_SESSION)) {
            $this->items =& $_SESSION;
        }
    }
}
