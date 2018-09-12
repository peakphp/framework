<?php

namespace Peak\DebugBar\Exception;

class ModuleNotFoundException extends \Exception
{
    /**
     * ModuleNotFoundException constructor.
     * @param string $module
     */
    public function __construct($module)
    {
        parent::__construct('Module '.trim(strip_tags($module)).' not found!');
    }
}
