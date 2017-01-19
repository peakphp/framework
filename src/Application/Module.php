<?php
namespace Peak\Application;

use Peak\Application;
use Peak\Application\Routing;
use Peak\Application\Config\AppTree;
use Peak\Registry;

/**
 * Module Application Loader
 *
 * A module is simply a complete app structure inside your main app.
 * Ideal for creating an admin panel / subsite / blog / ...
 */
class Module
{
    /**
     * Module name
     */
    public $name;

    public function __construct()
    {
        $app          = Registry::o()->app;
        $this->name   = $app->front->route->controller;
        $app->routing = new Routing(null, Application::conf('path.public').'/'.$this->name);

        $this->updateConfig();

        $this->init();

        $app->reload()->run();
    }

    public function init() {}

    /**
     * Update app view path for this controllers scripts
     */
    public function updateConfig()
    {
        //update applicatiom namespace to the module name 
        //folder under your App\Modules folder
        Application::conf()->set('ns', Application::conf('ns').'\Modules\\'.ucfirst($this->name));

        //update application tree
        $ap = new AppTree(APPLICATION_ABSPATH.'/modules/'.$this->name);
        Application::conf()->set('path.apptree', $ap->tree);
    }
}