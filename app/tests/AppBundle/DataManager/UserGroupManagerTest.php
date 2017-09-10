<?php

namespace Test\AppBundle\DataManager;

use AppBundle\DataManager\UserGroupManager;
use AppBundle\DataManager\UserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserGroup;
use AppBundle\Exception\AppException;
use AppBundle\Exception\DataManagerException;
use AppBundle\TestCase\AppTestCase;
use Doctrine\Common\DataFixtures\ReferenceRepository;

class UserGroupManagerTest extends AppTestCase
{
    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserGroupManager
     */
    private $userGroupManager;

    public function setUp()
    {
        $fixtures = array(
            'AppBundle\DataFixtures\ORM\UserFixture',
            'AppBundle\DataFixtures\ORM\UserGroupFixture',
        );
        $this->referenceRepository = $this->loadFixtures($fixtures)->getReferenceRepository();

        $this->userManager = $this->getContainer()->get('app.data_manager.user');
        $this->userGroupManager = $this->getContainer()->get('app.data_manager.user_group');
    }

    public function testGetGroupById()
    {
        $id = $this->referenceRepository->getReference('group-1')->getId();
        $this->assertNotNull($id);
        try {
            $userGroup = $this->userGroupManager->getGroupById($id);
            $this->assertTrue($userGroup instanceof UserGroup);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }
    }

    public function testCreateNewGroup()
    {
        try {
            $newGroup = $this->userGroupManager->createNewGroup($this->_faker->name);
            $this->assertTrue($newGroup instanceof UserGroup);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }
    }

    public function testAddMember()
    {
        $user = $this->getUser();
        $group = $this->getGroup();

        try {
            $result = $this->userGroupManager->addNewMember($group, $user);
            $this->assertTrue($result);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }
    }

    public function testRemoveMember() {
        $user = $this->getUser();
        $group = $this->getGroup();

        try {
            $this->userGroupManager->addNewMember($group, $user);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }

        try {
            $result = $this->userGroupManager->removeMember($group, $user);
            $this->assertTrue($result);
        } catch (AppException $exception) {
            $this->fail("Should throw data manager exception");
        }
    }

    public function testRemoveMemberNonExist() {
        $user = $this->getUser();
        $group = $this->getGroup();

        try {
            $this->userGroupManager->removeMember($group, $user);
            $this->fail("User is not in the group, should throw exception");
        } catch (DataManagerException $exception) {
            $this->assertTrue($exception instanceof DataManagerException);
        } catch (AppException $exception) {
            $this->fail("Should throw data manager exception");
        }
    }

    public function testRemoveGroup() {
        $user = $this->getUser();
        $group = $this->getGroup();

        try {
            $this->userGroupManager->addNewMember($group, $user);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }

        try {
            $this->userGroupManager->removeGroup($group);
            $this->fail("Show throw exception");
        } catch (AppException $exception) {
            $this->assertTrue($exception instanceof DataManagerException);
        }
    }

    public function testRemoveGroupNonEmpty() {
        $group = $this->getGroup();

        try {
            $result = $this->userGroupManager->removeGroup($group);
            $this->assertTrue($result);
        } catch (AppException $exception) {
            $this->fail("Should throw data manager exception");
        }
    }

    /**
     * Helper methods to get group
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return UserGroup|null
     */
    private function getGroup() {
        $groupId = $this->referenceRepository->getReference('group-1')->getId();

        try {
            $group = $this->userGroupManager->getGroupById($groupId);
            return $group;
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
            return null;
        }
    }

    /**
     * Helper methods to get user
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return User|null
     */
    private function getUser() {
        $id = $this->referenceRepository->getReference('user-1')->getId();

        try {
            $user = $this->userManager->getUserById($id);
            return $user;
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
            return null;
        }
    }
}