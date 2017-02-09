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
class ConfigCustomRoutes
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
                else if(is_string($r)) {

                    $parts = explode(' | ', $r);
                    if(count($parts) == 2) {

                        $ctrl_part = explode(Request::$separator, $parts[1]);

                        $collection[] = new CustomRoute(
                            trim($parts[0]),  // route
                            $ctrl_part[0],    // controller
                            (isset($ctrl_part[1]) ? $ctrl_part[1] : '') // action
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