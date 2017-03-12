<?php

namespace Peak\Bedrock\Application;

use Peak\Bedrock\Application;
use Peak\Bedrock\Application\Routing;
use Peak\Bedrock\Application\Config\AppTree;

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

    /**
     * Construtor
     */
    public function __construct()
    {
        $kernel          = Application::kernel();
        $this->name      = $kernel->front->route->controller;
        $kernel->routing = new Routing(null, relativePath(Application::conf('path.public').'/'.$this->name));
        
        $this->updateConfig()->init();

        $kernel->reload()->run();
    }

    public function init() {}

    /**
     * Update app view path for this controllers scripts
     *
     * @return $this
     */
    public function updateConfig()
    {
        //update applicatiom namespace to the module name 
        //folder under your App\Modules folder
        Application::conf()->set('ns', Application::conf('ns').'\Modules\\'.ucfirst($this->name));

        //update application tree
        $ap = new AppTree(APPLICATION_ABSPATH.'/modules/'.$this->name);
        Application::conf()->set('path.apptree', $ap->tree);

        return $this;
    }
}
