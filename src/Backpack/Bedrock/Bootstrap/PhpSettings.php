<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock\Bootstrap;

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
     * PhpSettings constructor.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Look for php prop to configure php setting on the fly via ini_set
     */
    public function boot()
    {
        if ($this->app->hasProp('php')) {
            new PhpIni($this->app->getProp('php'));
        }
    }
}