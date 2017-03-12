<?php

namespace Peak\Bedrock\Application\Bootstrap;

use Peak\Bedrock\Application;
use Peak\View;

/**
 * Application Bootstrap View
 */
class ConfigView
{
    /**
     * Configurate View based on Application config
     */
    public function __construct(View $view = null)
    {
        if (!isset($view) || !Application::conf()->have('view')) {
            return;
        }

        $cview = Application::conf('view');

        if (!empty($cview)) {
            foreach ($cview as $k => $v) {

                if (is_array($v)) {
                    foreach ($v as $p1 => $p2) $view->$k($p1,$p2);
                } else {
                    $view->$k($v);
                }
            }
        }
    }
}
