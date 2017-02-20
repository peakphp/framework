<?php
use PHPUnit\Framework\TestCase;

use Peak\Doorman\Manager;
use Peak\Doorman\User;
use Peak\Doorman\UserPolicy;

class UserPolicyTest extends TestCase
{
    function testCreateUser()
    {
        $manager = new Manager();
        $manager->setUserPolicy(
            new UserPolicy([
                $manager->createGroup('groupA'),
                $manager->createGroup('groupB'),
                $manager->createGroup('groupC'),
                $manager->createGroup('groupD'),
            ])
        );

        $userA = $manager->createUser('visitor');
        $userB = $manager->createUser('member');

        $this->assertTrue($userA->isInGroup('groupA'));
        $this->assertTrue($userA->isInGroup('groupB'));
        $this->assertTrue($userA->isInGroup('groupC'));
        $this->assertTrue($userA->isInGroup('groupD'));

        $this->assertTrue($userB->isInGroup('groupA'));
        $this->assertTrue($userB->isInGroup('groupB'));
        $this->assertTrue($userB->isInGroup('groupC'));
        $this->assertTrue($userB->isInGroup('groupD'));

        $this->assertFalse($userA->isInGroup('groupE'));
    }
}