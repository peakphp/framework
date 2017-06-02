<?php

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;
use Peak\Bedrock\Controller\ParentController;

/**
 * For standalone controller action class
 */
abstract class Child
{
    /**
     * View object
     * @var \Peak\Bedrock\View
     */
    protected $view;

    /**
     * Parent controller
     * @var \Peak\Bedrock\Controller\ParentController
     */
    protected $parent;

    /**
     * Constructor
     *
     * @param View $view
     * @param ParentController $parent
     */
    public function __construct(View $view, ParentController $parent)
    {
        $this->view = $view;
        $this->parent = $parent;

        // call child process() with di
        Application::container()->call(
            [$this, 'process']
        );
    }
}
