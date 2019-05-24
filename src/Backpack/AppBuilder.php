<?php

declare(strict_types=1);

namespace Peak\Backpack;

// This keep backward compatibility with version Peak 4.0.0-RC2 and below.
// This class alias will be deleted in next major version of Peak or even sooner (4.1.x and +)
// Please, use HttpAppBuilder instead.
class_alias(
    \Peak\Backpack\Bedrock\HttpAppBuilder::class,
    '\Peak\Backpack\AppBuilder'
);
