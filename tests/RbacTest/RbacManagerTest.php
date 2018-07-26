<?php

use PHPUnit\Framework\TestCase;

use Peak\Rbac\Manager;
use Peak\Rbac\User;
use Peak\Rbac\Role;
use Peak\Rbac\Permission;
use Peak\Rbac\CustomPermission;

class RbacManagerTest extends TestCase
{
    /**
     * test create user
     */  
    function testCreateUser()
    {
        $manager = new Manager();
        
        $manager->createUser('Bob');

        $this->assertTrue($manager->hasUser('Bob'));
        $this->assertFalse($manager->hasUser('John'));
    }

    /**
     * test add user
     */  
    function testAddUser()
    {
        $manager = new Manager();
        
        $bob = new User('Bob');
        $manager->addUser($bob);

        $this->assertTrue($manager->hasUser('Bob'));
        $this->assertFalse($manager->hasUser('John'));
    }

    /**
     * test get user
     */  
    function testGetUser()
    {
        $manager = new Manager();
        
        $manager->createUser('Bob');

        $this->assertTrue($manager->user('Bob') instanceof User);
        
        try {
            $unknow = $manager->user('Foo');
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

    /**
     * test create permission
     */
    function testCreatePerm()
    {
        $manager = new Manager();
        
        $perm = $manager->createPermission('Play to soccer');
        $this->assertTrue($perm instanceof Permission);

        try {
            $unknow = $manager->permission('Foo');
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));

        $this->assertTrue($manager->hasPermission('Play to soccer'));
    }

    /**
     * test create role
     */
    function testCreateRole()
    {
        $manager = new Manager();
        
        $perm = $manager->createPermission('Play to soccer');
        $bob = $manager->createUser('Bob');

        $adminrole = $manager->createRole('administrator');

        $this->assertFalse($manager->user('Bob')->hasRole($adminrole));
        $this->assertFalse($manager->user('Bob')->hasRole('administrator'));

        $bob->addRole($adminrole);

        $this->assertTrue($manager->user('Bob')->hasRole('administrator'));
        $this->assertTrue($manager->user('Bob')->hasRole($adminrole));

        try {
            $unknow = $manager->role('Foo');
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

    /**
     * User can with user
     */
    function testUserCan()
    {
        $manager = new Manager();
        
        $perm1     = $manager->createPermission('Play to soccer');
        $perm2     = $manager->createPermission('Touch the ball');

        $bob       = $manager->createUser('Bob');
        $john      = $manager->createUser('John');

        $adminrole = $manager->createRole('Administrator');
        $userrole  = $manager->createRole('User');



        $this->assertFalse($bob->can($perm1));

        $bob->addRole($adminrole);

        $this->assertFalse($bob->can($perm1));

        $perm1->addRole($adminrole);
        $perm2->addRole($userrole);
        $perm2->addRole($adminrole);

        $this->assertTrue($bob->can($perm1));
        $this->assertTrue($bob->can($perm2));

        $john->addRole($userrole);

        $this->assertFalse($john->can($perm1));
        $this->assertTrue($john->can($perm2));
    }

    /**
     * User can with manager
     */
    function testUserCan2()
    {
        $manager = new Manager();
        
        $perm1     = $manager->createPermission('Play to soccer');
        $perm2     = $manager->createPermission('Touch the ball');

        $bob       = $manager->createUser('Bob');
        $adminrole = $manager->createRole('administrator');

        $bob->addRole($adminrole);
        $perm1->addRole($adminrole);

        $result = $manager->userCan('Bob', [
            'Play to soccer',
            'Touch the ball',
        ]);

        $this->assertFalse($result);

        $this->assertFalse($manager->userCan('Bob', 'Touch the ball'));
        $this->assertTrue($manager->userCan('Bob', 'Play to soccer'));

        $perm2->addRole($adminrole);

        $result = $manager->userCan('Bob', [
            'Play to soccer',
            'Touch the ball',
        ]);

        $this->assertTrue($result);
    }

    /**
     * User custom permission
     */
    function testUserCustomPermission()
    {
        $manager = new Manager();
        
        $perm1     = $manager->createPermission('Play to soccer');
        $perm2     = $manager->createPermission('Touch the ball');

        $bob       = $manager->createUser('Bob');
        $adminrole = $manager->createRole('administrator');

        $perm1->addRole($adminrole);
        $bob->addRole($adminrole);

        $this->assertTrue($bob->can($perm1));
        $this->assertFalse($bob->can($perm2));

        // bypass perm1
        $custom_perm = new CustomPermission($perm1, CustomPermission::DENY);
        $bob->addCustomPermission($custom_perm);

        $this->assertFalse($bob->can($perm1));

        // bypass perm2
        $custom_perm = new CustomPermission($perm2, CustomPermission::ALLOW);
        $bob->addCustomPermission($custom_perm);

        $this->assertTrue($bob->can($perm2));

        // with multiple perm
        $result = $manager->userCan('Bob', [
            'Play to soccer',
            'Touch the ball',
        ]);

        $this->assertFalse($result);

        // reallow first perm
        $custom_perm = new CustomPermission($perm1, CustomPermission::ALLOW);
        $bob->addCustomPermission($custom_perm);

        // with multiple perm
        $result = $manager->userCan('Bob', [
            'Play to soccer',
            'Touch the ball',
        ]);

        $this->assertTrue($result);
    }

    /**
     * Manager addRoleToUser()
     */
    function testAddRoleToUser()
    {
        $manager = new Manager();

        $bob       = $manager->createUser('Bob');
        $adminrole = $manager->createRole('administrator');

        $manager->addRoleToUser('administrator', 'Bob');

        $this->assertTrue($manager->user('Bob')->hasRole('administrator'));
    }

    /**
     * Manager addRoleToUser()
     */
    function testAddRoleToPermission()
    {
        $manager = new Manager();

        $perm1     = $manager->createPermission('Play to soccer');
        $adminrole = $manager->createRole('administrator');

        $manager->addRoleToPermission('administrator', 'Play to soccer');

        $this->assertTrue($manager->permission('Play to soccer')->hasRole('administrator'));
    }
}