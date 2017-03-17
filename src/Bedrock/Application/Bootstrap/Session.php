<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application;

/**
 * Application session
 */
class Session
{
    /**
     * Name and start session
     */
    public function __construct()
    {
        if (!isCli()) {
            session_name(Application::conf('name'));
            session_start();
        }
    }
}
