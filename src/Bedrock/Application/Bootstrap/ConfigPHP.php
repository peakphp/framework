<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application;

/**
 * Application php config
 */
class ConfigPHP
{
    /**
     * Configurate PHP
     */
    public function __construct()
    {
        $php_settings = Application::conf('php');

        if (empty($php_settings)) {
            return;
        }

        foreach ($php_settings as $setting => $val) {
            if (!is_array($val)) {
                ini_set($setting, $val);
            } else {
                foreach ($val as $k => $v) {
                    ini_set($setting.'.'.$k, $v);
                }
            }
        }
    }
}
