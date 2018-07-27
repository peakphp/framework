<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Exceptions;

/**
 * Class BlockNotFoundException
 * @package Peak\Bedrock\View\Exceptions
 */
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
