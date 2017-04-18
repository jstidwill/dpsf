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
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/")
     * @Method("GET")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:User')->createQueryBuilder('u');

//        if ($request->query->getAlnum('filter')) {
//            $queryBuilder->where('bp.title LIKE :title')
//                ->setParameter('title', '%' . $request->query->getAlnum('filter') . '%');
//        }

        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');

        $users = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->json($users, Response::HTTP_OK, [], ['groups' => ['staff']]);

    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{uid}")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['staff']]);
    }
}
