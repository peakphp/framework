<?php

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;

/**
 * Parent Controller
 */
abstract class ParentController extends ActionController
{
    /**
     * Turn off since we don't inspect a method anymore
     * @var boolean
     */
    protected $actions_with_params = false;

    /**
     * Default Actions classes namespace
     * @var string
     */
    protected $actions_ns;

    /**
     * Make params accessible to child
     * @var array
     */
    public $params;

    /**
     * Make params_raw accessible to child
     * @var array
     */
    public $params_raw;

    /**
     * Loaded child action instance
     * @var object|null
     */
    public $child = null;

    /**
     * Constructor
     *
     * @param View   $view
     * @param string $ns if not set, use default app ns (ex: App\Controllers\[controllerTitle]\)
     */
    public function __construct(View $view, $ns = null)
    {
        parent::__construct($view);
        $this->actions_ns = (!isset($ns)) ? Application::conf('ns').'\Controllers\\'.$this->getTitle() : $ns;
    }

    /**
     * Get action class complete name
     *
     * @param  string $action
     * @return string
     */
    public function actionClass($action)
    {
        $action = substr($action, 1);
        return $this->actions_ns.'\\'.ucfirst($action).'Action';
    }

    /**
     * Check if action class name exists
     *
     * @param  string $name
     * @return bool
     */
    public function isAction($name)
    {
        return (class_exists($this->actionClass($name))) ? true : false;
    }

    /**
     * Call an action
     *
     * @param   string $action
     * @param   array  $args
     * @return  mixed
     */
    protected function callAction($action, $args = [])
    {
        $this->child = Application::create($this->actionClass($action), [$args], [
            'Peak\Bedrock\Controller\ParentController' => $this
        ]);
    }
}
