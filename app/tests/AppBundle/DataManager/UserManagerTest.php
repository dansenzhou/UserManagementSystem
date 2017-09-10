<?php

namespace Test\AppBundle\DataManager;

use AppBundle\DataManager\UserManager;
use AppBundle\Entity\User;
use AppBundle\Exception\AppException;
use AppBundle\TestCase\AppTestCase;
use Doctrine\Common\DataFixtures\ReferenceRepository;

class UserManagerTest extends AppTestCase
{
    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    /**
     * @var UserManager
     */
    private $userManager;

    public function setUp()
    {
        $fixtures = array(
            'AppBundle\DataFixtures\ORM\UserFixture',
        );
        $this->referenceRepository = $this->loadFixtures($fixtures)->getReferenceRepository();

        $this->userManager = $this->getContainer()->get('app.data_manager.user');
    }

    public function testGetUserById()
    {
        $id = $this->referenceRepository->getReference('user-1')->getId();
        $this->assertNotNull($id);
        try {
            $user = $this->userManager->getUserById($id);
            $this->assertTrue($user instanceof User);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }
    }

    public function testAddUser()
    {
        try {
            $user = $this->userManager->addUser($this->_faker->name);
            $this->assertTrue($user instanceof User);
        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }
    }

    public function testRemoveUser()
    {
        $id = $this->referenceRepository->getReference('user-1')->getId();
        $this->assertNotNull($id);


        try {
            $user = $this->userManager->getUserById($id);
            $this->assertTrue($user instanceof User);
            $result = $this->userManager->removeUser($user);
            $this->assertTrue($result);

        } catch (AppException $exception) {
            $this->fail("Exception should not throw");
        }
    }
}