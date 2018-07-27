<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;

/**
 * Class ConfigPHP
 * @package Peak\Bedrock\Application\Bootstrap
 */
class ConfigPHP
{
    /**
     * Configure PHP
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        if (empty($config->php)) {
            return;
        }

        foreach ($config->php as $setting => $val) {
            if (!is_array($val)) {
                ini_set($setting, (string)$val);
            } else {
                foreach ($val as $k => $v) {
                    ini_set($setting.'.'.$k, (string)$v);
                }
            }
        }
    }
}
