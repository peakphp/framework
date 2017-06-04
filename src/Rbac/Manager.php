<?php

namespace Peak\Rbac;

use Peak\Rbac\User;
use Peak\Rbac\Permission;
use Peak\Rbac\RolesHolder;

use \Exception;

class Manager
{
    use RolesHolder;

    /**
     * Users
     * @var array
     */
    protected $users = [];

    /**
     * Permissions
     * @var array
     */
    protected $permissions = [];

    /**
     * Create a user
     *
     * @param  string $id
     * @return User
     */
    public function createUser($id)
    {
        $this->addUser(new User($id));
        return $this->user($id);
    }

    /**
     * Add a user
     * @param User $user
     */
    public function addUser(User $user)
    {
        $this->users[$user->getId()] = $user;
        return $this;
    }

    /**
     * Has user
     *
     * @param  string  $id
     * @return boolean
     */
    public function hasUser($id)
    {
        return isset($this->users[$id]);
    }

    /**
     * Access to a user
     *
     * @param  string $id
     * @return User
     */
    public function user($id)
    {
        if (!isset($this->users[$id])) {
            throw new Exception(__CLASS__.': User ['.$id.'] not found');
        }
        return $this->users[$id];
    }

    /**
     * Create a permissions
     *
     * @param  string $id
     * @return Permission
     */
    public function createPermission($id, $desc = '')
    {
        $this->addPermission(new Permission($id, $desc));
        return $this->permission($id);
    }

    /**
     * Add a permission
     * @param Permission $user
     */
    public function addPermission(Permission $perm)
    {
        $this->permissions[$perm->getId()] = $perm;
        return $this;
    }

    /**
     * Has perm
     *
     * @param  string  $id
     * @return boolean
     */
    public function hasPermission($id)
    {
        return isset($this->permissions[$id]);
    }

    /**
     * Access to a perms
     *
     * @param  string $id
     * @return Permission
     */
    public function permission($id)
    {
        if (!isset($this->permissions[$id])) {
            throw new Exception(__CLASS__.': Permission ['.$id.'] not found');
        }
        return $this->permissions[$id];
    }

    /**
     * Create a roles
     *
     * @param  string $id
     * @return Role
     */
    public function createRole($id, $desc = '')
    {
        $this->addRole(new Role($id, $desc));
        return $this->role($id);
    }

    /**
     * Access to a role
     *
     * @param  string $id
     * @return Role
     */
    public function role($id)
    {
        if (!isset($this->roles[$id])) {
            throw new Exception(__CLASS__.': Role ['.$id.'] not found');
        }
        return $this->roles[$id];
    }

    /**
     * Add a stored role to a stored user
     *
     * @param string $role
     * @param string $user
     */
    public function addRoleToUser($role, $user)
    {
        $this->user($user)->addRole($this->role($role));
    }

    /**
     * Add a stored role to a stored permission
     *
     * @param string $role
     * @param string $permission
     */
    public function addRoleToPermission($role, $perm)
    {
        $this->permission($perm)->addRole($this->role($role));
    }

    /**
     * Check if a user has permission(s)
     * If multiple permissions, they must be all true, otherwise it return false
     *
     * @param  string $user
     * @param  mixed  $perms A permission string or array of permissions string
     * @return boolean
     */
    public function userCan($user, $perms)
    {
        if (is_array($perms)) {
            foreach ($perms as $perm) {
                if (!$this->user($user)->can($this->permission($perm))) {
                    return false;
                }
            }
            return true;
        }

        return $this->user($user)->can($this->permission($perms));
    }
}
