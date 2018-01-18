<?php

namespace Peak\Bedrock\View\Exceptions;

class BlockNotFoundException extends \Exception
{
    /**
     * NoRouteFoundException constructor.
     * @param string $block
     */
    public function __construct($block)
    {
        parent::__construct('Block '.trim(strip_tags($block)).' not found.');
    }
}
