<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ControllerException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Exception\AppException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserGroupsController extends AbstractController
{
    /**
     * Admin add group
     *
     * @Route("/admin/groups", name="add_group")
     * @Method({"POST"})
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     * @throws ControllerException
     */
    public function addGroupAction(Request $request)
    {
        $name = $request->request->get('name', null);
        if (null === $name) {
            throw new ControllerException("group name is empty");
        }
        try {
            $newGroup = $this->getUserGroupManager()->createNewGroup($name);
            return new Response(json_encode(['group' => $newGroup->getName()]));
        } catch (AppException $exception) {
            throw $exception;
        }
    }

    /**
     * Admin remove group
     *
     * @Route("/admin/groups/{groupUuid}", name="remove_group")
     * @Method({"DELETE"})
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $groupUuid
     * @return Response
     * @throws AppException
     * @throws ControllerException
     */
    public function removeGroupAction($groupUuid)
    {
        if (null === $groupUuid) {
            throw new ControllerException("group uuid is empty");
        }
        try {
            $group = $this->getUserGroupManager()->getGroupByUuid($groupUuid);
            $this->getUserGroupManager()->removeGroup($group);
            return new Response(json_encode(['deletedGroup' => $groupUuid]));
        } catch (AppException $exception) {
            throw $exception;
        }
    }

    /**
     * Admin add group members
     *
     * @Route("/admin/groups/{groupUuid}/members", name="add_group_member")
     * @Method({"POST"})
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param Request $request
     * @param $groupUuid
     * @return Response
     * @throws AppException
     * @throws ControllerException
     */
    public function addGroupMemberAction(Request $request, $groupUuid)
    {
        if (null === $groupUuid) {
            throw new ControllerException("group uuid is empty");
        }
        $userUuid = $request->request->get('userUuid', null);
        if (null === $userUuid) {
            throw new ControllerException("user uuid is empty");
        }
        try {
            $group = $this->getUserGroupManager()->getGroupByUuid($groupUuid);
            $user = $this->getUserManager()->getUserByUuid($userUuid);
            $this->getUserGroupManager()->addNewMember($group, $user);
            return new Response(json_encode(['newMember' => $user->getName()]));
        } catch (AppException $exception) {
            throw $exception;
        }
    }

    /**
     * Admin remove group member
     *
     * @Route("/admin/groups/{groupUuid}/members/{memberUuid}", name="remove_group_member")
     * @Method({"PUT"})
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $groupUuid
     * @param $memberUuid
     * @return Response
     * @throws AppException
     * @throws ControllerException
     */
    public function removeGroupMemberAction($groupUuid, $memberUuid)
    {
        if (null === $groupUuid) {
            throw new ControllerException("group uuid is empty");
        }
        if (null === $memberUuid) {
            throw new ControllerException("member uuid is empty");
        }
        try {
            $group = $this->getUserGroupManager()->getGroupByUuid($groupUuid);
            $user = $this->getUserManager()->getUserByUuid($memberUuid);
            $this->getUserGroupManager()->removeMember($group, $user);
            return new Response(json_encode(['removedMember' => $user->getName()]));
        } catch (AppException $exception) {
            throw $exception;
        }
    }
}