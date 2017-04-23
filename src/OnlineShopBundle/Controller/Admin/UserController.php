<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/admin/users/list", name="users_list")
     *
     * @return Response
     */
    public function listUsers()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy([], ['firstName' => 'ASC']);

        return $this->render('admin/users/list.html.twig', ['users' => $users]);
    }
}
