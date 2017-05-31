<?php

namespace Peak\Rbac;

use Peak\Rbac\User;
use Peak\Rbac\Permission;
use Peak\Rbac\AbstractRolesHolder;

use \Exception;

class Manager extends AbstractRolesHolder
{
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
     * @return Perm
     */
    public function permission($id)
    {
        if (!isset($this->permissions[$id])) {
            throw new Exception(__CLASS__.': Permission ['.$id.'] not found');
        }
        return $this->permissions[$id];
    }

    /**
     * Create a role
     *
     * @param  string $id
     * @return Role
     */
    public function createRole($id)
    {
        $this->addRole(new Role($id));
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
}