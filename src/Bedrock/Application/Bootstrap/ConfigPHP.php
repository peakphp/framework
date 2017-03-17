<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;

/**
 * Application php config
 */
class ConfigPHP
{
    /**
     * Configurate PHP
     *
     * @param Peak\Bedrock\Application\Config $config
     */
    public function __construct(Config $config)
    {
        if (empty($config->php)) {
            return;
        }

        foreach ($config->php as $setting => $val) {
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
