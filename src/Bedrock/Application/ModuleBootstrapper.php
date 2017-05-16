<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application\Bootstrapper;

/**
 * Application Bootstrapper
 */
class ModuleBootstrapper extends Bootstrapper
{
    /**
     * Empty default processes so it won't be called twice
     * @var array
     */
    protected $default_processes = [];
}
