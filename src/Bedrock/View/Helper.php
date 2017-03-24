<?php

namespace Peak\Bedrock\View;

use Peak\Bedrock\View;

/**
 * Peak View Helper Base
 */
abstract class Helper
{
    /**
     * View instance
     * @var Peak\Bedrock\View
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
