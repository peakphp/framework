<?php

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\AppContainer;

use Peak\Bedrock\Application;
use Peak\DebugBar\AbstractModule;

class AppContainer extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        $this->data->instances = Application::container()->getInstances();
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'App Container';
    }
}
