<?php
use PHPUnit\Framework\TestCase;

use Peak\Doorman\Ability;
use Peak\Doorman\Manager;
use Peak\Doorman\User;
use Peak\Doorman\SuperUser;
use Peak\Doorman\Group;
use Peak\Doorman\SuperGroup;
use Peak\Doorman\Permission;
use Peak\Doorman\Permissions;

class ManagerTest extends TestCase
{

    function testCreate()
    {
        $manager = new Manager();

        $root_user = $manager->user('root');

        $this->assertTrue($root_user instanceof SuperUser);

        $root_group = $manager->group('root');

        $this->assertTrue($root_group instanceof SuperGroup);
    }


    function testGetUnknown()
    {
        $manager = new Manager();

        $user = $manager->user('unknow_bob');
        $group = $manager->group('unknow_group');
        $ability = $manager->ability('unknow_ability');

        $this->assertTrue($user === null);
        $this->assertTrue($group === null);
        $this->assertTrue($ability === null);


    }

    function testExceptionRootUser()
    {
        try {
            $manager = new Manager();
            $create->createUser('root');
        }
        catch(\Exception $e) {
            $exception = true;
        }

        $this->assertTrue(isset($exception));
    }

    function testExceptionRootGroup()
    {
        try {
            $manager = new Manager();
            $create->createGroup('root');
        }
        catch(\Exception $e) {
            $exception = true;
        }

        $this->assertTrue(isset($exception));
    }


    function testCreateParseAbility()
    {

        $manager = new Manager();
            
        $ability = $manager->parseAbility('mysuperpower1 root:root 770');

        $this->assertTrue($ability instanceof Ability);

        $this->assertTrue(
            $manager->user('root')->can($ability, Permission::R)
        );

        // create bob
        $manager->createUser('bob');

        // check if bob can execute 'mysuperpower1' 
        $this->assertFalse(
            $manager->user('bob')->can($ability, Permission::E)
        );

        // add bob to root group
        $manager->user('bob')->addToGroup($manager->group('root'));

        // check if bob can execute 'mysuperpower1' 
        $this->assertTrue(
            $manager->user('bob')->can($ability, Permission::E)
        );

        // remove bon from root
        $manager->user('bob')->removeFromGroup($manager->group('root'));

        // check if bob can execute 'mysuperpower1' 
        $this->assertFalse(
            $manager->user('bob')->can($ability, Permission::E)
        );




        
    }
}