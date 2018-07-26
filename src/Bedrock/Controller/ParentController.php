<?php

declare(strict_types=1);

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Config;
use Peak\Bedrock\View;

/**
 * Parent Controller
 */
abstract class ParentController extends ActionController
{
    /**
     * Default Actions classes namespace
     * @var string
     */
    protected $actions_ns;

    /**
     * Loaded child action instance
     * @var object|null
     */
    public $child = null;

    /**
     * ParentController constructor.
     *
     * @param View $view
     * @param Config $config
     * @param null $ns
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
     */
    public function __construct(View $view, Config $config, $ns = null)
    {
        parent::__construct($view, $config);
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
     * @param string $action
     * @param array $args
     * @return mixed|void
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
     */
    protected function callAction($action, $args = [])
    {
        $this->child = Application::create($this->actionClass($action), [$args], [
            'Peak\Bedrock\Controller\ParentController' => $this
        ]);
    }
}
