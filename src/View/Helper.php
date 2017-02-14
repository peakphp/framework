<?php

namespace Peak\View;

use Peak\Registry;

/**
 * Peak View Helper Base
 */
abstract class Helper
{

    public $view;
    
    /**
     * Get view object
     */
    public function __construct()
    {
        $this->view = Registry::o()->view;
    } 
}
