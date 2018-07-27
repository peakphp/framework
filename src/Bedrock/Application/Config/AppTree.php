<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Config;

/**
 * Class AppTree
 * @package Peak\Bedrock\Application\Config
 */
class AppTree
{
    /**
     * Application paths tree
     * @var array
     */
    protected $tree;

    /**
     * Constructor
     *
     * @param string $root application tree root
     */
    public function __construct($root)
    {
        $this->defaultAppTree($root);
    }

    /**
     * Get application path tree
     * @return array
     */
    public function get()
    {
        return $this->tree;
    }

    /**
     * Generate application tree
     *
     * @param   string $root
     */
    protected function defaultAppTree($root)
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
