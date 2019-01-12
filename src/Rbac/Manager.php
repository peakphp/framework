<?php

declare(strict_types=1);

namespace Peak\Rbac;

use Peak\Rbac\Exception\PermissionNotFoundException;
use Peak\Rbac\Exception\RoleNotFoundException;
use Peak\Rbac\Exception\UserNotFoundException;
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
     * @param string $id
     * @return User
     * @throws UserNotFoundException
     */
    public function createUser(string $id): User
    {
        $this->addUser(new User($id));
        return $this->user($id);
    }

    /**
     * Add a user
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users[$user->getId()] = $user;
        return $this;
    }

    /**
     * Has user
     *
     * @param  string $id
     * @return boolean
     */
    public function hasUser(string $id): bool
    {
        return isset($this->users[$id]);
    }

    /**
     * Access to a user
     *
     * @param string $id
     * @return User
     * @throws UserNotFoundException
     */
    public function user(string $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException($id);
        }
        return $this->users[$id];
    }

    /**
     * Create a permissions
     *
     * @param string $id
     * @param string $desc
     * @return Permission
     * @throws Exception
     */
    public function createPermission(string $id, string $desc = ''): Permission
    {
        $this->addPermission(new Permission($id, $desc));
        return $this->permission($id);
    }

    /**
     * Add a permission
     * @param Permission $perm
     * @return $this
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
     * @param $id
     * @return mixed
     * @throws PermissionNotFoundException
     */
    public function permission($id)
    {
        if (!isset($this->permissions[$id])) {
            throw new PermissionNotFoundException($id);
        }
        return $this->permissions[$id];
    }

    /**
     * Create a roles
     *
     * @param string $id
     * @param string $desc
     * @return mixed
     * @throws RoleNotFoundException
     */
    public function createRole(string $id, string $desc = '')
    {
        $this->addRole(new Role($id, $desc));
        return $this->role($id);
    }

    /**
     * Access to a role
     *
     * @param string $id
     * @return mixed
     * @throws RoleNotFoundException
     */
    public function role(string $id)
    {
        if (!isset($this->roles[$id])) {
            throw new RoleNotFoundException($id);
        }
        return $this->roles[$id];
    }

    /**
     * Add a stored role to a stored user
     *
     * @param string $role
     * @param string $user
     * @throws RoleNotFoundException
     * @throws UserNotFoundException
     */
    public function addRoleToUser(string $role, string $user)
    {
        $this->user($user)->addRole($this->role($role));
    }

    /**
     * Add a stored role to a stored permission
     *
     * @param string $role
     * @param string $perm
     * @throws PermissionNotFoundException
     * @throws RoleNotFoundException
     */
    public function addRoleToPermission(string $role, string $perm)
    {
        $this->permission($perm)->addRole($this->role($role));
    }

    /**
     * Check if a user has permission(s)
     * If multiple permissions, they must be all true, otherwise it return false
     *
     * @param  string $user
     * @param  mixed  $perms A permission string or array of permissions string
     * @return bool
     * @throws PermissionNotFoundException
     * @throws UserNotFoundException
     */
    public function userCan(string $user, $perms): bool
    {
        if (!is_array($perms)) {
            $perms = [$perms];
        }

        foreach ($perms as $perm) {
            if (!$this->user($user)->can($this->permission($perm))) {
                return false;
            }
        }
        return true;
    }
}
