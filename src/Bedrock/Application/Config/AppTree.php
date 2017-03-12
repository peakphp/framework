<?php

namespace Peak\Bedrock\Application\Config;

class AppTree
{
    /**
     * App paths tree
     * @var array
     */
    public $tree;

    /**
     * Constructor
     *
     * @param string $root
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
            'views_helpers'  => $root.'/Views/Helpers',
            'views_scripts'  => $root.'/Views/Scripts',
            'views_layouts'  => $root.'/Views/Layouts',
            'views_cache'    => $root.'/Views/Cache'
        ];
    }
}
