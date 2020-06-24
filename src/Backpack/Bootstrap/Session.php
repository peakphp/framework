<?php

declare(strict_types=1);

namespace Peak\Backpack\Bootstrap;

use Exception;
use Peak\Blueprint\Bedrock\Application;
use Peak\Blueprint\Common\Bootable;
use function defined;
use function php_sapi_name;
use function session_name;
use function session_save_path;
use function session_set_save_handler;
use function session_start;
use function session_status;

class Session implements Bootable
{
    private Application $app;

    private string $sessionPropName;

    /**
     * Session constructor.
     * @param Application $app
     * @param string $sessionPropName
     */
    public function __construct(Application $app, string $sessionPropName = 'session')
    {
        $this->app = $app;
        $this->sessionPropName = $sessionPropName;
    }
    /**
     * @throws Exception
     */
    public function boot()
    {
        if ((php_sapi_name() === 'cli' || defined('STDIN'))) {
            return;
        }

        if (session_status() == PHP_SESSION_ACTIVE) {
            throw new Exception('Session is already started');
        }

        // save path
        if ($this->app->hasProp($this->sessionPropName.'.save_path')) {
            session_save_path($this->app->getProp($this->sessionPropName.'.save_path'));
        }

        // save handler class
        if ($this->app->hasProp($this->sessionPropName.'.save_handler')) {
            session_set_save_handler($this->app->getProp($this->sessionPropName.'.save_handler'));
        }

        // name the session
        if ($this->app->hasProp($this->sessionPropName.'.name')) {
            session_name($this->app->getProp($this->sessionPropName.'.name'));
        }

        // session options
        $options = [];
        if ($this->app->hasProp($this->sessionPropName.'.options')) {
            $options = $this->app->getProp($this->sessionPropName.'.options');
        }

        // start the session
        session_start($options);
    }
}
