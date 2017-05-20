<?php

namespace Peak\Doorman;

use Peak\Doorman\PermissionInterface;

/**
 * Permissions for user group and other
 */
class Permissions
{
    protected $user;
    protected $group;
    protected $others;

    /**
     * Static method of creating this object
     *
     * @param  array $perms
     * @return Permissions
     */
    public static function create(...$perms)
    {
        // user, group and other separated $var
        if (count($perms) == 3) {
            $user = $perms[0];
            $group = $perms[1];
            $others = $perms[2];
        } elseif (count($perms) == 1) { // user, group and other in one $var
            // support decimal (ex: 777)
            if (strlen($perms[0]) == 3) {
                $permstr = (string)$perms[0];
                $user = $permstr[0];
                $group = $permstr[1];
                $others = $permstr[2];
            }
        }

        if (!isset($user)) {
            //error
            throw new \Exception('Permissions creation fail, invalid permission format.');
        }

        return new self(
            new Permission($user),
            new Permission($group),
            new Permission($others)
        );
    }

    /**
     * Constructor
     *
     * @param PermissionInterface $user
     * @param PermissionInterface $group
     * @param PermissionInterface $others
     */
    public function __construct(PermissionInterface $user, PermissionInterface $group, PermissionInterface $others)
    {
        $this->user = $user;
        $this->group = $group;
        $this->others = $others;
    }

    /**
     * Get user decimal permission
     *
     * @return integer
     */
    public function getUserPerm()
    {
        return $this->user->get();
    }

    /**
     * Get group decimal permission
     *
     * @return integer
     */
    public function getGroupPerm()
    {
        return $this->group->get();
    }

    /**
     * Get others decimal permission
     *
     * @return integer
     */
    public function getOthersPerm()
    {
        return $this->others->get();
    }
}
