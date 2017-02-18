<?php

namespace Peak\Doorman;

use Peak\Doorman\User;

/**
 * Super user entity (aka root)
 */
class SuperUser extends User
{
    /**
     * Set user to root
     */
    public function __construct()
    {
        parent::__construct('root');
    }
}
