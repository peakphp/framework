<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\AppConfig;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Config;
use Peak\DebugBar\AbstractModule;
use Peak\Common\Collection\CollectionFlattener;

/**
 * Class AppConfig
 * @package Peak\Bedrock\View\Helper\DebugBar\Modules\AppConfig
 */
class AppConfig extends AbstractModule
{
    /**
     * Initialize block
     *
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
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
