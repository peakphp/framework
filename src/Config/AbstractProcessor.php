<?php

namespace Peak\Config;

abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Config processed content
     * @var array
     */
    protected $content = [];

    /**
     * Return content
     *
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }
}
