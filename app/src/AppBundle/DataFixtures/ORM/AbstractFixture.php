<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AbstractFixture extends \Doctrine\Common\DataFixtures\AbstractFixture
{
    protected $_faker;

    public function __construct()
    {
        $this->_faker = Factory::create();
    }

    function load(ObjectManager $manager) {

    }
}