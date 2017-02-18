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
    function testCreateUser()
    {
        $root_user  = new User('jane');
        $root_group = new Group('root');

        $root_user->addToGroup($root_group);

        $ability1 = new Ability(
            'danceonthefloor', //object unique name
            $root_user,  //object owner
            $root_group, //object group
            Permissions::create(777) //object rights (chmod style) by default
        );

        $root_user->addCustomAbility(
            new CustomUserAbility(
                $ability1, 
                Permissions::create(7,7,7)
            )
        );

        // $perm1 = (new PermissionResolver('---'))->get();

        // $this->assertTrue($perm1 == 0);

        // $root_user->addCustomAbility(
        //     new CustomUserAbility(
        //         $ability1, 
        //         Permissions::create('000')
        //     )
        // );

        $this->assertTrue($root_user->can($ability1, Permission::READ));

        // //$user1 = new User('andrew');
        // //$user1->addRight($userRight1);

        // //$resolver = new ObjectRightsResolver($root_user, $object_rights_1);

        // if($root_user->can($object_rights_1, Permission::READ)) {
        //     echo '&ROOT CAN READ OBJECT '.$object_rights_1->getName();
        // }
        // else {
        //     echo '$ROOT CANNOT READ OBJECT '.$object_rights_1->getName();
        // }

        // //print_r((int)$rootUser->can($userRight, Permission::READ));
        // echo "\n";




        // // $user2 = new User('bob');
        // // $user3 = new User('jane');

        // // $group1 = new Group('admin');
        // // $group2 = new Group('visitor');
        // // $group3 = new Group('member');

        // // $groups
        // //     ->add(new Group('admin'))
        // //     ->add(new Group('visitor'))
        // //     ->add(new Group('visitor'));

        // $awareness = new Awareness($groups);

        // $awareness->addGroup(new Group('admin'));

        // echo (int)$awareness->hasGroup('admin').' _++++++_ ';
        // echo (int)$awareness->hasGroup('test');

        // //print_r($awareness);

    }

}