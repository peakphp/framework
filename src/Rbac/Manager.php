<?php

declare(strict_types=1);

namespace Peak\Rbac;

use \Exception;
use Peak\Rbac\Exception\PermissionNotFoundException;
use Peak\Rbac\Exception\RoleNotFoundException;
use Peak\Rbac\Exception\UserNotFoundException;

/**
 * Class Manager
 * @package Peak\Rbac
 */
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
     * @param $id
     * @return User
     * @throws UserNotFoundException
     */
    public function createUser($id): User
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
     * @param  string  $id
     * @return boolean
     */
    public function hasUser($id): bool
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
    public function user($id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException($id);
        }
        return $this->users[$id];
    }

    /**
     * Create a permissions
     *
     * @param $id
     * @param string $desc
     * @return Permission
     * @throws Exception
     */
    public function createPermission($id, $desc = ''): Permission
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
     * @param $id
     * @param string $desc
     * @return mixed
     * @throws RoleNotFoundException
     */
    public function createRole($id, $desc = '')
    {
        $this->addRole(new Role($id, $desc));
        return $this->role($id);
    }

    /**
     * Access to a role
     *
     * @param $id
     * @return mixed
     * @throws RoleNotFoundException
     */
    public function role($id)
    {
        if (!isset($this->roles[$id])) {
            throw new RoleNotFoundException($id);
        }
        return $this->roles[$id];
    }

    /**
     * Add a stored role to a stored user
     *
     * @param $role
     * @param $user
     * @throws RoleNotFoundException
     * @throws UserNotFoundException
     */
    public function addRoleToUser($role, $user)
    {
        $this->user($user)->addRole($this->role($role));
    }

    /**
     * Add a stored role to a stored permission
     *
     * @param $role
     * @param $perm
     * @throws PermissionNotFoundException
     * @throws RoleNotFoundException
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
     * @return bool
     * @throws PermissionNotFoundException
     * @throws UserNotFoundException
     */
    public function userCan($user, $perms): bool
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
