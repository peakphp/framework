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
    ];

    /**
     * DebugBar constructor.
     */
    public function __construct()
    {
        $this->dbar = new DebugBarComponent($this->modules);
    }

    /**
     * Add module
     *
     * @param string $module
     * @return $this
     */
    public function addModule($module)
    {
        $this->dbar->addModule($module);
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
