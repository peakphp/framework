<?php

declare(strict_types=1);

namespace Peak\Http\Request;

use Psr\Http\Message\ServerRequestInterface;

class PreRoute extends Route
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request): bool
    {
        if (null !== $this->getMethod() && $this->getMethod() !== $request->getMethod()) {
            return false;
        }

        // compile pseudo route syntax {param} and {param}:type into valid regex
        $routeRegex = (new RouteExpression($this->getPath()))->getRegex();

        // look to match the route
        $this->pregMatch('#^'.$routeRegex.'#', $request->getUri()->getPath());

        return !empty($this->getMatches());
    }
}
