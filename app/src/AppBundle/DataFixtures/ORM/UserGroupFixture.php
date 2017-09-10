<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\UserGroup;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserGroupFixture extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load initial data into user group table
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $group = $this->generateDummyUserGroup();
            $this->addReference('group-' . $i, $group);
            $manager->persist($group);
        }

        $manager->flush();
    }

    /**
     * Generate dummy user group
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return UserGroup
     */
    public function generateDummyUserGroup()
    {
        $userGroup = new UserGroup();
        $userGroup->setName($this->_faker->name);

        return $userGroup;
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
