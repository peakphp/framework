<?php

namespace Peak\Doorman;

use Peak\Doorman\PermissionInterface;

/**
 * Permission base
 */
abstract class AbstractPermission implements PermissionInterface
{
    /**
     * Decimal permission constants
     */
    const READ    = 4;
    const R       = 4;
    const WRITE   = 2;
    const W       = 2;
    const EXECUTE = 1;
    const E       = 1;
    
    /**
     * Permission value
     */
    protected $permission;

    /**
     * Constructor
     *
     * @param mixed $perm
     */
    public function __construct($perm)
    {
        $this->set($perm);
    }
}
