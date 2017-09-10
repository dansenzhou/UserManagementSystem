<?php

namespace AppBundle\Controller;

use AppBundle\DataManager\UserGroupManager;
use AppBundle\DataManager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class AbstractController extends Controller
{

    /**
     * Get user manager
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return UserManager
     */
    protected function getUserManager()
    {
        $userManager = $this->get('app.data_manager.user');
        if ($userManager instanceof UserManager) {
            return $userManager;
        }
        throw new NotFoundHttpException("User manager not found");
    }

    /**
     * Get user group manager
     *
     * @author Dansen Zhou <zds8978704@gmail.com>
     *
     * @return UserGroupManager
     */
    protected function getUserGroupManager()
    {
        $userGroupManager = $this->get('app.data_manager.user_group');
        if ($userGroupManager instanceof UserGroupManager) {
            return $userGroupManager;
        }
        throw new NotFoundHttpException("User group manager not found");
    }
}