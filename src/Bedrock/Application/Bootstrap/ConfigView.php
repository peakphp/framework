<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\View;

/**
 * Application Bootstrap View
 */
class ConfigView
{
    /**
     * Configurate View based on Application config
     *
     * @param Peak\Bedrock\Application\Config $config
     * @param Peak\Bedrock\View               $view
     */
    public function __construct(Config $config, View $view = null)
    {
        if (!isset($view) || !isset($config->view)) {
            return;
        }

        if (!empty($config->view)) {
            foreach ($config->view as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $p1 => $p2) {
                        $view->$k($p1,$p2);
                    }
                } else {
                    $view->$k($v);
                }
            }
        }
    }
}
