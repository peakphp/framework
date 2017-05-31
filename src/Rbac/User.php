<?php

namespace Peak\Rbac;

use Peak\Rbac\AbstractRolesHolder;

class User extends AbstractRolesHolder
{
    /**
     * User custom permissions
     * @var array
     */
    protected $custom_perms = [];

    /**
     * Check if user has a permission
     *
     * @param  Permission $perm
     * @return bool
     */
    public function can(Permission $perm)
    {
        $can = false;

        foreach ($this->roles as $role) {
            if ($perm->hasRole($role)) {
                $can = true;
            }
        }

        return $can;
    }
}
