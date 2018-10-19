<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock\DebugBar\AppContainer;

use Peak\Blueprint\Bedrock\Application;
use Peak\DebugBar\AbstractModule;

/**
 * Class AppContainer
 * @package Peak\Bedrock\View\Helper\DebugBar\Modules\AppContainer
 */
class AppContainer extends AbstractModule
{
    /**
     * @var Application
     */
    private $application;

    /**
     * AppConfig constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        parent::__construct();
    }

    /**
     * Initialize block
     */
    public function initialize()
    {
        $this->data->instances = $this->application->getKernel()->getContainer()->getInstances();
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
