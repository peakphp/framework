<?php

declare(strict_types=1);

namespace Peak\Routing;

/**
 * Class RequestServerURI
 * @package Peak\Routing
 */
class RequestServerURI extends Request
{
    /**
     * Overload Request constructor using $_SERVER request uri
     */
    public function __construct($base_uri = null)
    {
        $request_uri = isCli() ? '' : filter_var($_SERVER['REQUEST_URI']);
        parent::__construct($request_uri, $base_uri);
    }
}
