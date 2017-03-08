<?php

namespace Peak\Application\Bootstrap;

use Peak\Application;
use Peak\Registry;

/**
 * Application Bootstrap View
 */
class ConfigView
{
    /**
     * Configurate View based on Application config
     */
    public function __construct()
    {
        if (!Application::conf()->have('view') || !Registry::isRegistered('view')) {
            return;
        }

        $view  = Registry::o()->view;
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
