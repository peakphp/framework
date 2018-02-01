<?php

use Peak\Bedrock\Application\Bootstrapper;

/**
 * App Bootstrapper
 */
class Bootstrap extends Bootstrapper
{

    protected $processes = [

    ];

    /**
     * Init method
     */
    public function initMethod()
    {
        $this->init_method = true;
    }
}