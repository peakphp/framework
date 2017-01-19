<?php

namespace Peak\Application\Config;

class AppTree
{
    public $tree;

    /**
     * Constructor
     * 
     * @param
     */
    public function __construct($root)
    {
        $this->defaultAppTree($root);
    }

     /**
     * Generate default application tree
     *
     * @param   string $root
     * @return  array
     */
    public function defaultAppTree($root)
    {
        $this->tree = [
            'root'         => $root,
            'cache'               => $root.'/cache',
            'controllers'         => $root.'/controllers',
            'models'              => $root.'/models',
            'modules'             => $root.'/modules',
            'langs'               => $root.'/langs',
            'views'               => $root.'/views',
            'views_ini'           => $root.'/views/ini',
            'views_helpers'       => $root.'/views/helpers',
            'views_themes'        => $root.'/views',
            'theme'               => $root.'/views',
            'theme_scripts'       => $root.'/views/scripts',
            'theme_partials'      => $root.'/views/partials',
            'theme_layouts'       => $root.'/views/layouts',
            'theme_cache'         => $root.'/views/cache'
        ];
    }
}