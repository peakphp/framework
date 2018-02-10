<?php

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\AppConfig;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Config;
use Peak\DebugBar\AbstractModule;
use Peak\Common\CollectionFlattener;

class AppConfig extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        $config = Application::container()->get(Config::class);
        $this->data->config = (new CollectionFlattener($config))->flatAll();
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'App Config';
    }
}
