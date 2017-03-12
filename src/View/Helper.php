<?php

namespace Peak\View;

use Peak\View;

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
    public function __construct(View $view)
    {
        $this->view = $view;
    } 
}
