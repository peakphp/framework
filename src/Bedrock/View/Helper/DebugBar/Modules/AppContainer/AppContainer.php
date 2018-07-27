<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\AppContainer;

use Peak\Bedrock\Application;
use Peak\DebugBar\AbstractModule;

/**
 * Class AppContainer
 * @package Peak\Bedrock\View\Helper\DebugBar\Modules\AppContainer
 */
class AppContainer extends AbstractModule
{
    /**
     * Initialize block
     *
     * @throws Application\Exceptions\InstanceNotFoundException
     * @throws Application\Exceptions\MissingContainerException
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
