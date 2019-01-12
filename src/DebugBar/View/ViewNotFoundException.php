<?php

namespace Peak\DebugBar\View;

use \Exception;

class ViewNotFoundException extends Exception
{
    /**
     * ViewNotFoundException constructor.
     * @param string $viewFile
     */
    public function __construct(string $viewFile)
    {
        parent::__construct('View file '.trim($viewFile).' not found.');
    }
}
