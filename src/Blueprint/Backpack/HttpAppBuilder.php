<?php

declare(strict_types=1);

namespace Peak\Blueprint\Backpack;

use Peak\Blueprint\Bedrock\HttpApplication;
use Peak\Blueprint\Common\ResourceResolver;

interface HttpAppBuilder extends AppBuilderBase
{
    public function setHandlerResolver(ResourceResolver $handlerResolver);
    public function build(): HttpApplication;
}