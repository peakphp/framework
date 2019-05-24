<?php

declare(strict_types=1);

namespace Peak\Blueprint\Backpack;

use Peak\Blueprint\Bedrock\CliApplication;

interface CliAppBuilder extends AppBuilderBase
{
    public function build(): CliApplication;
}