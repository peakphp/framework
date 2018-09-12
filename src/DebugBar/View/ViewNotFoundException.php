<?php

namespace Peak\DebugBar\View;

use \Exception;

class ViewNotFoundException extends Exception
{
    /**
     * ViewNotFoundException constructor.
     * @param $view_file
     */
    public function __construct($view_file)
    {
        parent::__construct('View file '.trim($view_file).' not found.');
    }
}
