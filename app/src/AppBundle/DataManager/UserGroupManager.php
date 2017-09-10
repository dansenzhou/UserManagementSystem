<?php

namespace AppBundle\DataManager;

use AppBundle\Entity\User;
use AppBundle\Entity\UserGroup;
use AppBundle\Exception\DatabaseException;
use AppBundle\Exception\DataManagerException;
use AppBundle\Repository\UserGroupRepository;
use Doctrine\DBAL\DBALException;

class UserGroupManager extends AbstractManager
{
    /**
     * @var UserGroupRepository $userGroupRepository
     */
    private $userGroupRepository;

    /**
     * Get group by id
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $id
     * @return UserGroup
     * @throws DataManagerException
     */
    public function getGroupById($id)
    {
        $group = $this->userGroupRepository->find($id);
        if ($group instanceof UserGroup) {
            return $group;
        } else {
            throw new DataManagerException("Get group by id: group not found");
        }
    }

    /**
     * Get group by uuid
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $uuid
     * @return UserGroup
     * @throws DataManagerException
     */
    public function getGroupByUuid($uuid)
    {
        $group = $this->userGroupRepository->findOneBy(['uuid' => $uuid]);
        if ($group instanceof UserGroup) {
            return $group;
        } else {
            throw new DataManagerException("Get group by uuid: group not found");
        }
    }

    /**
     * Create new group
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $name
     * @return UserGroup
     * @throws DatabaseException
     */
    public function createNewGroup($name)
    {
        $group = new UserGroup();
        $group->setName($name);
        $this->_entityManager->persist($group);
        try {
            $this->_entityManager->flush();
            return $group;
        } catch (DBALException $exception) {
            throw new DatabaseException("Fail to add new group: " . $exception);
        }
    }

    /**
     * Add new member to group
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param UserGroup $group
     * @param User $user
     * @return bool
     * @throws DataManagerException
     * @throws DatabaseException
     */
    public function addNewMember(UserGroup $group, User $user)
    {
        if ($group->getMembers()->contains($user)) {
            throw new DataManagerException("User has been in the group");
        }
        $group->addMember($user);
        $this->_entityManager->persist($group);
        try {
            $this->_entityManager->flush();
            return true;
        } catch (DBALException $exception) {
            throw new DatabaseException("Fail to add new member: " . $exception);
        }
    }

    /**
     * Remove member from group
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param UserGroup $group
     * @param User $user
     * @return bool
     * @throws DataManagerException
     * @throws DatabaseException
     */
    public function removeMember(UserGroup $group, User $user)
    {
        if (!$group->getMembers()->contains($user)) {
            throw new DataManagerException("User is not in the group");
        }
        $group->removeMember($user);
        $this->_entityManager->persist($group);
        try {
            $this->_entityManager->flush();
            return true;
        } catch (DBALException $exception) {
            throw new DatabaseException("Fail to remove member: " . $exception);
        }
    }

    /**
     * Remove group
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param UserGroup $group
     * @return bool
     * @throws DataManagerException
     * @throws DatabaseException
     */
    public function removeGroup(UserGroup $group)
    {
        if (sizeof($group->getMembers()) > 0) {
            throw new DataManagerException("Group is not empty");
        }

        $this->_entityManager->remove($group);
        try {
            $this->_entityManager->flush();
            return true;
        } catch (DBALException $exception) {
            throw new DatabaseException("Fail to remove member: " . $exception);
        }
    }

    /**
     * @param UserGroupRepository $userGroupRepository
     */
    public function setUserGroupRepository(UserGroupRepository $userGroupRepository)
    {
        $this->userGroupRepository = $userGroupRepository;
    }
}