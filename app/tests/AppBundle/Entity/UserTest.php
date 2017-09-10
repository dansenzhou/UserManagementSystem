<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testUuid()
    {
        $user = $this->getUser();
        $this->assertNotNull($user->getUuid());
    }

    public function testName()
    {
        $user = $this->getUser();
        $this->assertNull($user->getName());
        $expected = "test";
        $user->setName($expected);
        $this->assertSame($expected, $user->getName());
    }

    public function testCreated()
    {
        $user = $this->getUser();
        $this->assertNull($user->getCreated());
        $expect = new \DateTime();
        $user->setCreated($expect);
        $this->assertSame($expect, $user->getCreated());
    }

    public function testUpdated()
    {
        $user = $this->getUser();
        $this->assertNull($user->getUpdated());
        $expect = new \DateTime();
        $user->setUpdated($expect);
        $this->assertSame($expect, $user->getUpdated());
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->getMockForAbstractClass('AppBundle\Entity\User');
    }

}