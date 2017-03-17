<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;

/**
 * Application session
 */
class Session
{
    /**
     * Name and start session
     *
     * @param Peak\Bedrock\Application\Config $config
     */
    public function __construct(Config $config)
    {
        if (!isCli()) {
            if (isset($config['php']['session.save_path'])) {
                session_save_path($config['php']['session.save_path']);
            }
            session_name($config->name);
            session_start();
        }
    }
}
