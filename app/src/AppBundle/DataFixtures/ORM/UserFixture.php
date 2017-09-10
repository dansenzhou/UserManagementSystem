<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load initial data into user table
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = $this->generateDummyUser();
            $this->addReference('user-' . $i, $user);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * Generate dummy user
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return User
     */
    public function generateDummyUser()
    {
        $user = new User();
        $user->setName($this->_faker->name);

        return $user;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}
