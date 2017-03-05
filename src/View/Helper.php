<?php

namespace Peak\View;

use Peak\Registry;

/**
 * Peak View Helper Base
 */
abstract class Helper
{
    /**
     * View instance
     * @var Peak\View
     */
    public $view;
    
    /**
     * Get view object
     */
    public function __construct()
    {
        $this->view = Registry::o()->view;
    } 
}
