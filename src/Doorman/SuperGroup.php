<?php

namespace Peak\Doorman;

use Peak\Doorman\Group;

/**
 * Super group entity (aka root)
 */
class SuperGroup extends Group
{
    /**
     * Set group to root
     */
    public function __construct()
    {
        parent::__construct('root');
    }
}
