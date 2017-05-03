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


    function testGetUserAndGroup()
    {
        $manager = new Manager();

        $user = $manager->user('unknow_bob');
        $group = $manager->group('unknow_group');
        $ability = $manager->ability('unknow_ability');

        $this->assertTrue($user === null);
        $this->assertTrue($group === null);
        $this->assertTrue($ability === null);

        $this->assertTrue($manager->user('root') instanceof User);
        $this->assertTrue($manager->group('root') instanceof Group);

        $this->assertTrue($manager->group('root') instanceof SuperGroup);
        $this->assertTrue($manager->user('root') instanceof SuperUser);
    }

    function testHasUserAndGroup()
    {
        $manager = new Manager();

        $user = $manager->user('unknow_bob');
        $group = $manager->group('unknow_group');
        $ability = $manager->ability('unknow_ability');

        $this->assertFalse($manager->hasUser('bob'));
        $this->assertTrue($manager->hasUser('root'));
        $this->assertTrue($manager->hasGroup('root'));
        $this->assertFalse($manager->hasGroup('editor'));
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

    function testCreateAbility()
    {
        $manager = new Manager();


        $ability = $manager->createAbility('myability', 'root', 'root', Permissions::create(7,7,7));
      

        $this->assertTrue($ability instanceof Ability);
    }

    function testCreateAbilityException()
    {
        $manager = new Manager();

        // unknow group
        try {
            $manager->createAbility('myability', 'root', 'unknowgroup', Permissions::create(7,7,7));
        } catch(\Exception $e) {
            $error1 = true;
        }

        $this->assertTrue(isset($error1));

        // unknow user
        try {
            $manager->createAbility('myability', 'unknowuser', 'unknowgroup', Permissions::create(7,7,7));
        } catch(\Exception $e) {
            $error2 = true;
        }

        $this->assertTrue(isset($error2));

        //already existing ability
        $manager->createAbility('myability', 'root', 'root', Permissions::create(7,7,7));

        try {
            $manager->createAbility('myability', 'root', 'root', Permissions::create(7,7,7));
        } catch(\Exception $e) {
            $error3 = true;
        }

        $this->assertTrue(isset($error3));
    }


    function testHasAbility()
    {
        $manager = new Manager();


        $ability = $manager->createAbility('myability', 'root', 'root', Permissions::create(7,7,7));
      

        $this->assertTrue($manager->hasAbility('myability'));
        $this->assertFalse($manager->hasAbility('my_second_ability'));
    }


}