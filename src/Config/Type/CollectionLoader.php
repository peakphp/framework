<?php

namespace Peak\Config\Type;

use Peak\Common\Collection;
use Peak\Config\Loader;

class CollectionLoader extends Loader
{
    /**
     * Constructor
     * @param $config
     */
    public function __construct(Collection $config)
    {
        $this->config = $config;
        $this->content = $config->toArray();
    }
}
