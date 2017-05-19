<?php

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use Peak\Bedrock\Controller\Action;

/**
 * Parent Controller
 */
abstract class ParentController extends Action
{
    /**
     * Turn off since we don't inspect a method anymore
     * @var boolean
     */
    protected $actions_with_params = false;

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
     * Get action class complete name
     *
     * @param  string $action
     * @return string
     */
    public function actionClass($action)
    {
        $action = substr($action, 1);
        return 'App\Controllers\\'.$this->getTitle().'\\'.ucfirst($action).'Action';
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
        Application::instantiate($this->actionClass($action), [], [
            'Peak\Bedrock\Controller\ParentController' => $this
        ]);
    }
}
