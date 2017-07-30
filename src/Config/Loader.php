<?php

namespace Peak\Config;

class Loader implements LoaderInterface
{
    /**
     * Config resource
     * @var string
     */
    protected $config;

    /**
     * Config resource content
     * @var array
     */
    protected $content = [];

    /**
     * Return content
     *
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }
}
