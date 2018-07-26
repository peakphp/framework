<?php

declare(strict_types=1);

namespace Peak\Bedrock\Application\Exceptions;

/**
 * Class NoRouteFoundException
 * @package Peak\Bedrock\Application\Exceptions
 */
class NoRouteFoundException extends \Exception
{
    /**
     * NoRouteFoundException constructor
     * @param string $request
     */
    public function __construct($request)
    {
        parent::__construct('No route found for request ['.trim(strip_tags($request)).']');
    }
}
