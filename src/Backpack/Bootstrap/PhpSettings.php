<?php

declare(strict_types=1);

namespace Peak\Backpack\Bootstrap;

use Exception;
use Peak\Blueprint\Bedrock\Application;
use Peak\Blueprint\Common\Bootable;
use Peak\Common\PhpIni;

class PhpSettings implements Bootable
{
    private Application $app;

    private string $phpPropName;

    /**
     * PhpSettings constructor.
     * @param Application $app
     * @param string $phpPropName
     */
    public function __construct(Application $app, string $phpPropName = 'php')
    {
        $this->app = $app;
        $this->phpPropName = $phpPropName;
    }

    /**
     * Configure php setting on the fly via ini_set
     * @throws Exception
     */
    public function boot()
    {
        new PhpIni($this->app->getProp($this->phpPropName, []));
    }
}
