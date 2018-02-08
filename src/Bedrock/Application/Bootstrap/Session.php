<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;

/**
 * Application session
 */
class Session
{
    /**
     * Setup and start session
     *
     * @param \Peak\Bedrock\Application\Config $config
     */
    public function __construct(Config $config)
    {
        if (isCli()) {
            return;
        }

        if (session_status() == PHP_SESSION_ACTIVE) {
            throw new \Exception('Session is already started');
        }

        // save path
        if (isset($config['session']['save_path'])) {
            session_save_path($config['session']['save_path']);
        }

        // save handler class
        if (isset($config['session']['save_handler'])) {
            session_set_save_handler($config['session']['save_handler']);
        }

        // name the session
        if (isset($config['session']['name'])) {
            session_name($config['session']['name']);
        }

        // session options
        $options = [];
        if (isset($config['session']['options'])) {
            $options = $config['session']['options'];
        }

        // start the session
        session_start($options);
    }
}
