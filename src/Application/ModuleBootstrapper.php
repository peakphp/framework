<?php

namespace Peak\Application;

use Peak\Application\Bootstrapper;

/**
 * Application Bootstrapper
 */
class ModuleBootstrapper extends Bootstrapper
{
    /**
     * Empty default processes so it want called twice
     * @var array
     */
    protected $default_processes = [];

}
