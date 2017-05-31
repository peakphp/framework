<?php

use PHPUnit\Framework\TestCase;

use Peak\Rbac\Manager;
use Peak\Rbac\User;
use Peak\Rbac\Role;
use Peak\Rbac\Permission;

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
    }

    /**
     * test create role
     */
    function testCreateRole()
    {
        $manager = new Manager();
        
        $perm = $manager->createPermission('Play to soccer');
        $bob = $manager->createUser('Bob');

        $adminrole = new Role('administrator');

        $this->assertFalse($manager->user('Bob')->hasRole($adminrole));
        $this->assertFalse($manager->user('Bob')->hasRole('administrator'));

        $bob->addRole($adminrole);

        $this->assertTrue($manager->user('Bob')->hasRole('administrator'));
        $this->assertTrue($manager->user('Bob')->hasRole($adminrole));
    }

    /**
     * User can...
     */
    function testUserCan()
    {
        $manager = new Manager();
        
        $perm = $manager->createPermission('Play to soccer');
        $bob = $manager->createUser('Bob');

        $adminrole = new Role('administrator');

        $this->assertFalse($bob->can($perm));

        $bob->addRole($adminrole);

        $this->assertFalse($bob->can($perm));

        $perm->addRole($adminrole);

        $this->assertTrue($bob->can($perm));
    }
}