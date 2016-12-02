<?php
namespace Peak\Application\Bootstrap;

use Peak\Application;
use Peak\Registry;

/**
 * Application Bootstrap Router
 */
class Router
{

    /**
     * Configurate Router based on Application config
     */
    public function __construct()
    {
        if(!Application::conf()->have('router.addregex') || 
            !Registry::isRegistered('router')) return;

        $r      = Registry::o()->router;
        $routes = Application::conf('router.addregex');

        if(!empty($routes)) {
            foreach($routes as $i => $exp) {
                $parts = explode(' | ', $exp);
                if(count($parts) == 2) {
                    $r->addRegex(trim($parts[0]), trim($parts[1]));

                }
            }
        }
    }
}