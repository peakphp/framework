<?php

declare(strict_types=1);

namespace Peak\Backpack\Bootstrap;

use Peak\Blueprint\Common\Bootable;
use Peak\Blueprint\Bedrock\Application;
use Peak\Common\PhpIni;

class PhpSettings implements Bootable
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var string
     */
    private $phpPropName;

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
     */
    public function boot()
    {
        new PhpIni($this->app->getProp($this->phpPropName, []));
    }
}