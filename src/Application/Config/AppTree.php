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
            'root'           => $root,
            'cache'          => $root.'/Cache',
            'controllers'    => $root.'/Controllers',
            'models'         => $root.'/Models',
            'modules'        => $root.'/Modules',
            'langs'          => $root.'/Langs',
            'views'          => $root.'/Views',
            'views_ini'      => $root.'/Views/Ini',
            'views_helpers'  => $root.'/Views/Helpers',
            'views_themes'   => $root.'/Views',
            'theme'          => $root.'/Views',
            'theme_scripts'  => $root.'/Views/Scripts',
            'theme_partials' => $root.'/Views/Partials',
            'theme_layouts'  => $root.'/Views/Layouts',
            'theme_cache'    => $root.'/Views/Cache'
        ];
    }
}