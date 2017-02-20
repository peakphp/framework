<?php
use PHPUnit\Framework\TestCase;

use Peak\Doorman\Awareness;
use Peak\Doorman\User;
use Peak\Doorman\SuperUser;
use Peak\Doorman\Ability;
use Peak\Doorman\AbilityResolver;
use Peak\Doorman\CustomUserAbility;
use Peak\Doorman\Permission;
use Peak\Doorman\PermissionResolver;
use Peak\Doorman\Permissions;
use Peak\Doorman\Group;
use Peak\Doorman\SuperGroup;

class DoormanTest extends TestCase
{
    
    function testCreateSuperUser()
    {
        $root_user  = new SuperUser();
        $root_group = new SuperGroup();

        $ability1 = new Ability(
            'dosomething', //object unique name
            $root_user,  //object owner
            $root_group, //object group
            Permissions::create(0,0,0) //object rights (chmod style) by default
        );

        $this->assertTrue($root_user->can($ability1, Permission::READ));
        $this->assertTrue($root_user->can($ability1, Permission::WRITE));
        $this->assertTrue($root_user->can($ability1, Permission::EXECUTE));
    }

    function testCreateFakeSuperUser()
    {
        $root_user  = new User('root');
        $root_group = new SuperGroup();

        $ability1 = new Ability(
            'dosomething', //object unique name
            $root_user,  //object owner
            $root_group, //object group
            Permissions::create(0,0,0) //object rights (chmod style) by default
        );

        $this->assertFalse($root_user->can($ability1, Permission::READ));
        $this->assertFalse($root_user->can($ability1, Permission::WRITE));
        $this->assertFalse($root_user->can($ability1, Permission::EXECUTE));
    }


    /**
     * test new instance
     */  
    function testCreateUserWithGroups()
    {
        $root_user = new SuperUser();
        $root_group = new SuperGroup();

        $user  = new User('jane');
        $groupA = new Group('groupA');
        $groupB = new Group('groupB');
        $groupC = new Group('groupC');

        $user->addToGroup($groupA, $groupB, $groupC);
       
        $this->assertTrue($user->isInGroup('groupA'));
        $this->assertTrue($user->isInGroup('groupB'));
        $this->assertTrue($user->isInGroup('groupC'));

        $this->assertFalse($user->isInGroup('groupD'));

    }

    function testUserCan()
    {
        $root_user = new SuperUser();
        $root_group = new SuperGroup();

        $user  = new User('jane');
        $group = new Group('groupA');

        $user->addToGroup($group);

        $ability1 = new Ability(
            'danceonthefloor', //object unique name
            $user,  //object owner
            $group, //object group
            Permissions::create(0,0,0) //object rights (chmod style) by default
        );

        $this->assertFalse($user->can($ability1, Permission::READ));
        $this->assertFalse($user->can($ability1, Permission::WRITE));
        $this->assertFalse($user->can($ability1, Permission::EXECUTE));

        $this->assertTrue($user->abilityPermission($ability1) == 0);

        // overload permissions
        $user->addCustomAbility(
            new CustomUserAbility(
                $ability1, 
                Permissions::create(7,7,7)
            )
        );

        $this->assertTrue($user->can($ability1, Permission::READ));
        $this->assertTrue($user->can($ability1, Permission::WRITE));
        $this->assertTrue($user->can($ability1, Permission::EXECUTE));
    }

    function testCreateAbility()
    {
        $root_user = new SuperUser();
        $root_group = new SuperGroup();

        $user  = new User('jane');
        $group = new Group('groupA');

        $user->addToGroup($group);

        $ability1 = new Ability(
            'danceonthefloor', //object unique name
            $user,  //object owner
            $group, //object group
            Permissions::create(0,0,0) //object rights (chmod style) by default
        );

        $this->assertFalse($user->can($ability1, Permission::READ));
        $this->assertFalse($user->can($ability1, Permission::WRITE));
        $this->assertFalse($user->can($ability1, Permission::EXECUTE));


        $this->assertTrue($user->abilityPermission($ability1) == 0);
    }

}