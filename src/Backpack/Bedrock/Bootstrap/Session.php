<?php

declare(strict_types=1);

namespace Peak\Backpack\Bedrock\Bootstrap;

use Peak\Blueprint\Bedrock\Application;
use Peak\Blueprint\Common\Bootable;

/**
 * Class Session
 * @package Peak\Backpack\Bedrock\Bootstrap
 */
class Session implements Bootable
{
    /**
     * @var Application
     */
    private $application;

    /**
     * Session constructor.
     * @param Application $application
     * @throws \Exception
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }
    /**
     * Setup and start session from app props
     *
     * @param Application $config
     * @throws \Exception
     */
    public function boot()
    {
        if ((php_sapi_name() === 'cli' || defined('STDIN'))) {
            return;
        }

        if (session_status() == PHP_SESSION_ACTIVE) {
            throw new \Exception('Session is already started');
        }

        // save path
        if ($this->application->hasProp('session.save_path')) {
            session_save_path($this->application->getProp('session.save_path'));
        }

        // save handler class
        if ($this->application->hasProp('session.save_handler')) {
            session_set_save_handler($this->application->getProp('session.save_handler'));
        }

        // name the session
        if ($this->application->hasProp('session.name')) {
            session_name($this->application->getProp('session.name'));
        }

        // session options
        $options = [];
        if ($this->application->hasProp('session.options')) {
            $options = $this->application->getProp('session.options');
        }

        // start the session
        session_start($options);
    }
}
