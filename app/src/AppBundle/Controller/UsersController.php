<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ControllerException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Exception\AppException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends AbstractController
{
    /**
     * Admin add user
     *
     * @Route("/admin/users", name="add_user")
     * @Method({"POST"})
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param Request $request
     * @return Response
     * @throws AppException
     * @throws ControllerException
     */
    public function addUserAction(Request $request)
    {
        $name = $request->request->get('name', null);
        if (null === $name) {
            throw new ControllerException("user name is empty");
        }
        try {
            $user = $this->getUserManager()->addUser($name);
            return new Response(json_encode(['user' => $user->getName()]));
        } catch (AppException $exception) {
            throw $exception;
        }
    }

    /**
     * Admin remove user
     *
     * @Route("/admin/users/{userUuid}", name="remove_user")
     * @Method({"DELETE"})
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @param $userUuid
     * @return Response
     * @throws AppException
     * @throws ControllerException
     */
    public function removeUserAction($userUuid)
    {
        if (null === $userUuid) {
            throw new ControllerException("user uuid is empty");
        }
        try {
            $user = $this->getUserManager()->getUserByUuid($userUuid);
            $this->getUserManager()->removeUser($user);
            return new Response(json_encode(['deletedUser' => $userUuid]));
        } catch (AppException $exception) {
            throw $exception;
        }
    }
}