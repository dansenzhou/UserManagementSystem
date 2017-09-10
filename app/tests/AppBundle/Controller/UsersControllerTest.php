<?php

namespace Test\AppBundle\Controller;

use AppBundle\TestCase\AppTestCase;
use Doctrine\Common\DataFixtures\ReferenceRepository;

class UsersControllerTest extends AppTestCase
{
    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    public function setUp()
    {
        $fixtures = array(
            'AppBundle\DataFixtures\ORM\UserFixture'
        );
        $this->referenceRepository = $this->loadFixtures($fixtures)->getReferenceRepository();
    }

    public function testAddUserAction()
    {
        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/users', ['name' => $this->_faker->name()]);

        $this->isSuccessful($client->getResponse());
        $this->assertTrue(array_key_exists("user", $this->parseJsonContent($client->getResponse()->getContent())));
    }

    public function testAddUserWithoutPermissionAction()
    {
        $client = $this->makeClient();
        $client->request('POST', '/admin/users', ['name' => $this->_faker->name()]);

        $this->isFailure($client->getResponse());
    }

    public function testAddUserWithoutNameAction()
    {
        $client = $this->makeAdminClient();
        $client->request('POST', '/admin/users');

        $this->isFailure($client->getResponse());
        $this->assertTrue($this->hasError($client->getResponse()->getContent()));
    }

    public function testAddUserWithGETAction()
    {
        $client = $this->makeAdminClient();
        $client->request('GET', '/admin/users');

        $this->isFailure($client->getResponse());
    }

    public function testRemoveUserAction()
    {
        $client = $this->makeAdminClient();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $client->request('DELETE', '/admin/users/' . $userUuid);

        $this->isSuccessful($client->getResponse());
        $this->assertTrue(array_key_exists("deletedUser", $this->parseJsonContent($client->getResponse()->getContent())));
    }

    public function testRemoveUserWithoutPermissionAction()
    {
        $client = $this->makeClient();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $client->request('DELETE', '/admin/users/' . $userUuid);

        $this->isFailure($client->getResponse());
    }

    public function testRemoveUserWithoutUuidAction()
    {
        $client = $this->makeAdminClient();
        $client->request('DELETE', '/admin/users');

        $this->isFailure($client->getResponse());
    }

    public function testRemoveUserWithPOSTAction()
    {
        $client = $this->makeAdminClient();
        $userUuid = $this->referenceRepository->getReference('user-1')->getUuid();
        $client->request('POST', '/admin/users/' . $userUuid);
        $this->isFailure($client->getResponse());
    }
}