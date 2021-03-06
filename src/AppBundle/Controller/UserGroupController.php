<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserGroupController extends Controller
{
    /**
     * Finds and displays a user entity.
     *
     * @Route("/{uid}/groups")
     * @Method("GET")
     *
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function showAction(User $user)
    {
        $roles = $this->getDoctrine()->getManager()->getRepository('AppBundle:UserGroup')->findBy(['user' => $user]);

        return $this->json($roles, Response::HTTP_OK, [], ['groups' => ['staff']]);
    }
}
