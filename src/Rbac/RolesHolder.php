<?php

declare(strict_types=1);

namespace Peak\Rbac;

/**
 * Trait RolesHolder
 * @package Peak\Rbac
 */
trait RolesHolder
{
    /**
     * Roles of the holder
     * @var array
     */
    protected $roles = [];

    /**
     * Add role
     *
     * @param  Role $role
     * @return $this
     */
    public function addRole(Role $role)
    {
        $this->roles[$role->getId()] = $role;
        return $this;
    }

    /**
     * Has role
     *
     * @param  mixed $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if ($role instanceof Role) {
            return isset($this->roles[$role->getId()]);
        }
        return isset($this->roles[$role]);
    }
}
