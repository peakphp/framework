<?php
namespace Peak\Application\Bootstrap;

use Peak\Application;
use Peak\Registry;
use Peak\Collection;
use Peak\Exception;
use Peak\Routing\Request;
use Peak\Routing\CustomRoute;

/**
 * Application Bootstrap Customer routes
 */
class CustomRoutes
{
    /**
     * Configurate View based on Application config
     */
    public function __construct()
    {
        $routes = Application::conf('routes');

        $collection = new Collection();

        if(!empty($routes)) {
            foreach($routes as $r) {
                if(isset($r['route']) && isset($r['controller']) && isset($r['action'])) {

                    $collection[] = new CustomRoute(
                        $r['route'],
                        $r['controller'],
                        $r['action']
                    );


                }
                else if(isset($r[0]) && is_string($r[0])) {

                    $parts = explode(' | ', $r[0]);
                    if(count($parts) == 2) {

                        $ctrl_part = explode(Request::$separator, $parts[1]);

                        $r['route']      = trim($parts[0]);
                        $r['controller'] = $ctrl_part[0];
                        $r['action']     = (isset($ctrl_part[1])) ? $ctrl_part[1] : '';

                        $collection[] = new CustomRoute(
                            $r['route'],
                            $r['controller'],
                            $r['action']
                        );

                    }
                    else throw new Exception('ERR_CUSTOM', 'Invalid routing expression');
                }

                else {
                    throw new Exception('ERR_CUSTOM', 'Invalid routing in your application config');
                }
            }
        }

        Registry::o()->app->routing->custom_routes = $collection;
    }

}