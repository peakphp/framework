<?php

namespace Peak\Doorman;

use Peak\Doorman\AbstractPermission;
use Peak\Doorman\PermissionResolver;

/**
 * Decimal permission
 */
class Permission extends AbstractPermission
{
    /**
     * Permission decimal
     */
    protected $permission;

    /**
     * Get permission decimal value
     * 
     * @return integer
     */
    public function get()
    {
        return $this->permission;
    }

    /**
     * Set the permission decimal value
     * 
     * @param mixed $perm
     */
    public function set($perm)
    {
        $this->permission = (new PermissionResolver($perm))->get();
    }
}
