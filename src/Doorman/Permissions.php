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
     * Static method of creating this object. 
     * 
     * @param  array $perms 
     * @return Permissions
     */
    public static function create(...$perms)
    {
        if(count($perms) == 3) {
            $user = $perms[0];
            $group = $perms[1];
            $others = $perms[2];
        }
        elseif(count($perms) == 1) {
            if(strlen($perms[0]) == 3) {
                $user = ((string)$perms[0])[0];
                $group = ((string)$perms[0])[1];
                $others = ((string)$perms[0])[2];
            }
        }

        if(!isset($user)) {
            //error
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
