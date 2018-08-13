<?php

declare(strict_types=1);

namespace Peak\Rbac;

/**
 * Class User
 * @package Peak\Rbac
 */
class User extends AbstractHolder
{
    use RolesHolder;

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
    public function can(Permission $perm): bool
    {
        // if a custom permission exists for permission, bypass role checks
        if (isset($this->custom_perms[$perm->getId()])) {
            return $this->custom_perms[$perm->getId()]->isAllowed();
        }

        foreach ($this->roles as $role) {
            if ($perm->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add a custom permission to bypass a permission
     *
     * @param  CustomPermission $cperm
     * @return $this
     */
    public function addCustomPermission(CustomPermission $customPermission)
    {
        $this->custom_perms[$customPermission->getId()] = $customPermission;
        return $this;
    }
}
