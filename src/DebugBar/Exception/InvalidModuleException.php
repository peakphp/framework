<?php

namespace Peak\DebugBar\Exception;

class InvalidModuleException extends \Exception
{
    /**
     * InvalidModuleException constructor.
     * @param $module
     */
    public function __construct($module)
    {
        parent::__construct(trim(strip_tags($module)).' must be an instance of AbstractModule');
    }
}
