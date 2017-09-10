<?php

namespace AppBundle\DataManager;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;


abstract class AbstractManager
{
    /** @var EntityManagerInterface $_entityManager */
    protected $_entityManager;

    /** @var Logger $_logger */
    protected $_logger;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->_entityManager = $entityManager;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }

}
