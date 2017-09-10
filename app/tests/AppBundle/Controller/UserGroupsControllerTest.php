<?php

namespace Test\AppBundle\Controller;

use AppBundle\DataManager\UserGroupManager;
use AppBundle\DataManager\UserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserGroup;
use AppBundle\Exception\AppException;
use AppBundle\TestCase\AppTestCase;
use Doctrine\Common\DataFixtures\ReferenceRepository;

class UserGroupsControllerTest extends AppTestCase
{
    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    /**
     * @var UserGroupManager
     */
    private $userGroupManager;

    /**
     * @var UserManager
     */
    private $userManager;

    public function setUp()
    {
        $fixtures = array(
            'AppBundle\DataFixtures\ORM\UserFixture',
            'AppBundle\DataFixtures\ORM\UserGroupFixture',
        );
        $this->referenceRepository = $this->loadFixtures($fixtures)->getReferenceRepository();
        $this->userGroupManager = $this->getContainer()->get('app.data_manager.user_group');
        $this->userManager = $this->getContainer()->get('app.data_manager.user');
    }

    public function testAddGroupAction()
    {
        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups', ['name' => $this->_faker->name()]);

        $this->isSuccessful($client->getResponse());
        $this->assertTrue(array_key_exists("group", $this->parseJsonContent($client->getResponse()->getContent())));
    }

    public function testAddGroupWithoutPermissionAction()
    {
        $client = $this->makeClient();
        $client->request('POST', '/admin/groups', ['name' => $this->_faker->name()]);

        $this->isFailure($client->getResponse());
    }

    public function testAddGroupWithoutNameAction()
    {
        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups');

        $this->isFailure($client->getResponse());
        $this->assertTrue($this->hasError($client->getResponse()->getContent()));
    }

    public function testRemoveGroupAction()
    {
        $client = $this->makeAdminClient();
        $uuid = $this->referenceRepository->getReference('group-1')->getUuid();
        $client->request('DELETE', '/admin/groups/' . $uuid);

        $this->isSuccessful($client->getResponse());
        $this->assertTrue(array_key_exists("deletedGroup", $this->parseJsonContent($client->getResponse()->getContent())));
    }

    public function testRemoveNoneExistGroupAction()
    {
        $client = $this->makeAdminClient();
        $client->request('DELETE', '/admin/groups/xxxx');

        $this->isFailure($client->getResponse());
    }

    public function testRemoveGroupWithoutPermissionAction()
    {
        $client = $this->makeClient();
        $uuid = $this->referenceRepository->getReference('group-1')->getUuid();
        $client->request('DELETE', '/admin/groups/' . $uuid);

        $this->isFailure($client->getResponse());
    }

    public function testRemoveGroupWithoutUuidAction()
    {
        $client = $this->makeAdminClient();
        $client->request('DELETE', '/admin/groups');

        $this->isFailure($client->getResponse());
    }

    public function testRemoveNoneEmptyGroupAction()
    {
        $this->addUser1InGroup1();

        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();
        $client = $this->makeAdminClient();
        $client->request('DELETE', '/admin/groups/' . $groupUuid);

        $this->isFailure($client->getResponse());
    }

    public function testAddGroupMemberAction()
    {
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups/' . $groupUuid . '/members', ['userUuid' => $userUuid]);

        $this->isSuccessful($client->getResponse());
        $this->assertTrue(array_key_exists("newMember", $this->parseJsonContent($client->getResponse()->getContent())));
    }

    public function testAddGroupMemberWithoutPermissionAction()
    {
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeClient();
        $client->request('POST', '/admin/groups/' . $groupUuid . '/members', ['userUuid' => $userUuid]);

        $this->isFailure($client->getResponse());
    }

    public function testAddGroupMemberNoneExistUserAction()
    {
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups/' . $groupUuid . '/members', ['userUuid' => "xxxx"]);

        $this->isFailure($client->getResponse());
    }

    public function testAddGroupMemberNoneExistGroupAction()
    {
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups/' . 'xxxx' . '/members', ['userUuid' => $userUuid]);

        $this->isFailure($client->getResponse());
    }

    public function testAddGroupMemberNoneUserIdAction()
    {
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();
        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups/' . $groupUuid . '/members');

        $this->isFailure($client->getResponse());
    }

    public function testAddGroupMemberAlreadyExistMemberAction()
    {
        $this->addUser1InGroup1();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/groups/' . $groupUuid . '/members', ['userUuid' => $userUuid]);

        $this->isFailure($client->getResponse());
        $this->assertTrue($this->hasError($client->getResponse()->getContent()));
    }

    public function testRemoveGroupMemberAction()
    {
        $this->addUser1InGroup1();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('PUT', '/admin/groups/' . $groupUuid . '/members/' . $userUuid);

        $this->isSuccessful($client->getResponse());
        $this->assertTrue(array_key_exists("removedMember", $this->parseJsonContent($client->getResponse()->getContent())));
    }

    public function testRemoveGroupMemberWithoutPermissionAction()
    {
        $this->addUser1InGroup1();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeClient();
        $client->request('PUT', '/admin/groups/' . $groupUuid . '/members/' . $userUuid);

        $this->isFailure($client->getResponse());
    }

    public function testRemoveGroupMemberNoneExistUserAction()
    {
        $this->addUser1InGroup1();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('PUT', '/admin/groups/' . $groupUuid . '/members/' . "xxxx");

        $this->isFailure($client->getResponse());
    }

    public function testRemoveGroupMemberNoneExistGroupAction()
    {
        $this->addUser1InGroup1();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('PUT', '/admin/groups/' . 'xxxx' . '/members/' . $userUuid);

        $this->isFailure($client->getResponse());
    }

    public function testRemoveGroupMemberNoneExistMemberAction()
    {
        $this->addUser1InGroup1();
        $userUuid = $this->referenceRepository->getReference('user-2')->getUuid();
        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();

        $client = $this->makeAdminClient();
        $client->request('PUT', '/admin/groups/' . $groupUuid . '/members/' . $userUuid);

        $this->isFailure($client->getResponse());
        $this->assertTrue($this->hasError($client->getResponse()->getContent()));
    }

    /**
     * Helper methods to pre-add user 1 in group 1
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     */
    private function addUser1InGroup1()
    {
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $user = $this->userManager->getUserByUuid($userUuid);
        $this->assertTrue($user instanceof User);

        $groupUuid = $this->referenceRepository->getReference('group-1')->getUuid();
        $group = $this->userGroupManager->getGroupByUuid($groupUuid);
        $this->assertTrue($group instanceof UserGroup);

        try {
            $this->userGroupManager->addNewMember($group, $user);
        } catch (AppException $exception) {
            $this->fail("Should not throw any exception");
        }
    }
}