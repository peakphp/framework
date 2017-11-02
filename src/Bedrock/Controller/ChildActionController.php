<?php

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;

/**
 * For standalone controller action class
 */
abstract class ChildActionController
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

    /**
     * Check method exists in parent
     *
     * @param $method
     * @param null $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args = null)
    {
        if (!method_exists($this->parent, $method)) {
            $line = debug_backtrace()[0]['line'];
            throw new Exception('Method '.$method.'() not found in '.get_class($this).' on line '.$line);
        }
        return call_user_func_array([$this->parent, $method], $args);
    }
}
