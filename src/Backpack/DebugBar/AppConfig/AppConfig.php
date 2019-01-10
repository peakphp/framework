<?php

declare(strict_types=1);

namespace Peak\Backpack\DebugBar\AppConfig;

use Peak\Blueprint\Bedrock\Application;
use Peak\Collection\CollectionFlattener;
use Peak\DebugBar\AbstractModule;

class AppConfig extends AbstractModule
{
    /**
     * @var Application
     */
    private $application;

    /**
     * AppConfig constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
        parent::__construct();
    }

    /**
     * Initialize
     */
    public function initialize()
    {
        $config = $this->application->getProps();
        if (!is_null($config)) {
            $this->data->config = (new CollectionFlattener($config))->flatAll();
        }
    }

    /**
     * Render tab title
     * @return string
     */
    public function renderTitle()
    {
        return 'App Config';
    }
}
