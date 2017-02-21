<?php

namespace Peak\Doorman;

use Exception;

use Peak\Collection;
use Peak\Doorman\Ability;
use Peak\Doorman\User;
use Peak\Doorman\UserPolicy;
use Peak\Doorman\SuperUser;
use Peak\Doorman\Group;
use Peak\Doorman\SuperGroup;
use Peak\Doorman\Permissions;
use Peak\Doorman\PolicySubjectInterface;

/**
 * Wrap and simplify usage of doorman ability permissions component
 */
class Manager implements PolicySubjectInterface
{
    /**
     * Users
     * @var Collection
     */
    protected $users;

    /**
     * Groups
     * @var Collection
     */
    protected $groups;

    /**
     * Abilities
     * @var Collection
     */
    protected $abilities;

    /**
     * User policy
     * @var UserPolicy
     */
    protected $user_policy = null;

    /**
     * Prepare mananger
     *
     * @param  Peak\Doorman\UserPolicy $up
     * @param  Peak\Doorman\ManagerPolicy $mp
     */
    public function __construct(UserPolicy $up = null, ManagerPolicy $mp = null)
    {
        $this->users     = new Collection();
        $this->groups    = new Collection();
        $this->abilities = new Collection();

        
        if(!isset($mp)) {
            $mp = new ManagerPolicy();
            $mp->create($this);
        }

        if(isset($up)) {
            $this->user_policy = $up;
        }
    }

    /**
     * Add a user policy
     * 
     * @param  Peak\Doorman\UserPolicy $up
     * @return $this
     */
    public function setUserPolicy(UserPolicy $up)
    {
        $this->user_policy = $up;
        return $this;
    }

    /**
     * Create a user
     * 
     * @param  string $name 
     * @return Peak\Doorman\User      
     */
    public function createUser($name)
    {
        if(isset($this->users->$name)) {
            throw new Exception(__CLASS__.': User '.htmlspecialchars($name).' already exists');
        } 
        else {
            $this->users[$name] = new User($name);

            if(isset($this->user_policy)) {
                $this->user_policy->create($this->users[$name]);
            }

            return $this->users[$name];
        }
    }

    /**
     * Add a user object instance
     * 
     * @param  Peak\Doorman\User $user
     * @return Peak\Doorman\User 
     */
    public function addUser(User $user)
    {
        $name = $user->getName();

        if(isset($this->users->$name)) {
            throw new Exception(__CLASS__.': User '.htmlspecialchars($name).' already exists');
        } 
        else {
            $this->users[$name] = $user;

            if(isset($this->user_policy)) {
                $this->user_policy->create($user);
            }

            return $user;
        }
    }

    /**
     * Get a user
     * 
     * @param  string $name
     * @return Peak\Doorman\User|null
     */
    public function user($name)
    {
        return $this->users->$name;
    }

    /**
     * Check if user exists
     * 
     * @param  string  $name 
     * @return boolean       
     */
    public function hasUser($name)
    {
        return isset($this->users->$name);
    }

    /**
     * Create a group
     * 
     * @param  string $name 
     * @return Peak\Doorman\Group      
     */
    public function createGroup($name)
    {
        if(isset($this->groups->$name)) {
            throw new Exception(__CLASS__.': Group '.htmlspecialchars($name).' already exists');
        } else {
            $this->groups[$name] = new Group($name);
            return $this->groups[$name];
        }
    }

    /**
     * Add a group object instance
     * 
     * @param  Peak\Doorman\Group $group 
     * @return Peak\Doorman\Group      
     */
    public function addGroup(Group $group)
    {
        $name = $name = $group->getName();

        if(isset($this->groups->$name)) {
            throw new Exception(__CLASS__.': Group '.htmlspecialchars($name).' already exists');
        } else {
            $this->groups[$name] = $group;
            return $group;
        }
    }

    /**
     * Get a group
     * 
     * @param  string $name
     * @return Peak\Doorman\Group|null
     */
    public function group($name)
    {
        return $this->groups->$name;
    }

    /**
     * Check if group exists
     * 
     * @param  string  $name 
     * @return boolean       
     */
    public function hasGroup($name)
    {
        return isset($this->groups->$name);
    }

    /**
     * Create an ability
     * 
     * @param  string      $ability   
     * @param  string      $username  
     * @param  string      $groupname 
     * @param  Permissions $perms
     * @return Peak\Doorman\Ability            
     */
    public function createAbility($ability, $username, $groupname, Permissions $perms)
    {
        if(isset($this->abilities->$ability)) {
            throw new Exception(__CLASS__.': Ability '.htmlspecialchars($ability).' already exists');
        } elseif(!$this->hasUser($username)) {
            throw new Exception(__CLASS__.': User '.htmlspecialchars($username).' doesn\'t exists');
        } elseif(!$this->hasUser($groupname)) {
            throw new Exception(__CLASS__.': Group '.htmlspecialchars($groupname).' doesn\'t exists');
        }

        $this->abilities[$ability] = new Ability(
            $ability,
            $this->user($username),
            $this->group($groupname),
            $perms
        );

        return $this->abilities[$ability];
    }

    /**
     * Create an ability using the string format:
     * [ability_name] [user]:[group] [permissions]
     * 
     * @param  string $ability_str
     * @return Peak\Doorman\Ability            
     */
    public function parseAbility($ability_str)
    {
        $ability_parts = explode(' ', $ability_str);

        if(count($ability_parts) == 3) {

            $usergroup = explode(':', $ability_parts[1]); 

            if(count($usergroup) != 2) {
                //exception
                throw new Exception(__CLASS__.': Invalid ability string format for user/group');
            }

            return $this->createAbility(
                $ability_parts[0],
                $usergroup[0],
                $usergroup[1],
                Permissions::create($ability_parts[2])
            );
        }
        else {
            //exception
            throw new Exception(__CLASS__.': Invalid ability string format');
        }
    }

    /**
     * Get an ability
     * 
     * @param  string $name
     * @return Peak\Doorman\Ability|null
     */
    public function ability($name)
    {
        return $this->abilities->$name;
    }

    /**
     * Check if ability exists
     * 
     * @param  string  $name 
     * @return boolean       
     */
    public function hasAbility($name)
    {
        return isset($this->abilities->$name);
    }
}
