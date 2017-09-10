<?php

namespace AppBundle\DataManager;

use AppBundle\Entity\UserGroup;
use AppBundle\Entity\User;
use AppBundle\Exception\DatabaseException;
use AppBundle\Exception\DataManagerException;
use AppBundle\Repository\UserRepository;
use Doctrine\DBAL\DBALException;

class UserManager extends AbstractManager
{
    /**
     * @var UserRepository $userRepository
     */
    private $userRepository;

    /**
     * Get user by id
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $id
     * @return User
     * @throws DataManagerException
     */
    public function getUserById($id)
    {
        $user = $this->userRepository->find($id);
        if ($user instanceof User) {
            return $user;
        } else {
            throw new DataManagerException("Get user by id: user not found");
        }
    }

    /**
     * Get user by uuid
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $uuid
     * @return User
     * @throws DataManagerException
     */
    public function getUserByUuid($uuid)
    {
        $user = $this->userRepository->findOneBy(['uuid' => $uuid]);
        if ($user instanceof User) {
            return $user;
        } else {
            throw new DataManagerException("Get user by uuid: user not found");
        }
    }

    /**
     * Add new user
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $name
     * @return User
     * @throws DatabaseException
     */
    public function addUser($name)
    {
        $user = new User();
        $user->setName($name);
        $this->_entityManager->persist($user);
        try {
            $this->_entityManager->flush();
            return $user;
        } catch (DBALException $exception) {
            throw new DatabaseException("Fail to add new user: " . $exception);
        }
    }

    /**
     * Remove user
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param User $user
     * @return bool
     * @throws DatabaseException
     */
    public function removeUser(User $user)
    {
        if (sizeof($user->getGroups()) > 0) {
            foreach ($user->getGroups() as $group) {
                if ($group instanceof UserGroup) {
                    $group->removeMember($user);
                }
            }
        }
        $this->_entityManager->remove($user);
        try {
            $this->_entityManager->flush();
            return true;
        } catch (DBALException $exception) {
            throw new DatabaseException("Fail to delete user: " . $exception);
        }
    }

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
}