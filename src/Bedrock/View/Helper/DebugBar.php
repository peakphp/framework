<?php

namespace Peak\Bedrock\View\Helper;

use Peak\DebugBar\DebugBar as DebugBarComponent;

/**
 * Wrapper around DebugBar component
 */
class DebugBar
{
    /**
     * @var DebugBarComponent
     */
    protected $dbar;

    /**
     * @var array
     */
    protected $modules = [
        \Peak\Bedrock\View\Helper\DebugBar\Modules\Version\Version::class,
        \Peak\DebugBar\Modules\ExecutionTime\ExecutionTime::class,
        \Peak\DebugBar\Modules\Memory\Memory::class,
        \Peak\DebugBar\Modules\Message\Message::class,
        \Peak\DebugBar\Modules\Files\Files::class,
        \Peak\Bedrock\View\Helper\DebugBar\Modules\ViewVars\ViewVars::class,
        \Peak\DebugBar\Modules\Session\Session::class,
        \Peak\DebugBar\Modules\Inputs\Inputs::class,
        \Peak\Bedrock\View\Helper\DebugBar\Modules\AppContainer\AppContainer::class,
        \Peak\Bedrock\View\Helper\DebugBar\Modules\AppConfig\AppConfig::class,
        \Peak\DebugBar\Modules\UserConstants\UserConstants::class,
    ];

    /**
     * DebugBar constructor.
     */
    public function __construct()
    {
        $this->dbar = new DebugBarComponent(null, $this->modules);
    }

    /**
     * Add module
     *
     * @see DebugBar::addModule()
     */
    public function addModule($module)
    {
        $this->dbar->addModule($module);
        return $this;
    }

    /**
     * Add modules
     *
     * @see DebugBar::addModules()
     */
    public function addModules($modules)
    {
        $this->dbar->addModule($modules);
        return $this;
    }

    /**
     * Render and output the bar
     */
    public function render()
    {
        echo $this->dbar->render();
    }
}
