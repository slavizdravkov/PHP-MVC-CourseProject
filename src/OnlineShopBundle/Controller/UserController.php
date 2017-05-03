<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Cart;
use OnlineShopBundle\Entity\Role;
use OnlineShopBundle\Entity\User;
use OnlineShopBundle\Entity\UserStatus;
use OnlineShopBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     *
     * @return Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $defaultRole = $roleRepository->findOneBy(['name' => 'ROLE_USER']);
            $user->addRole($defaultRole);

            $statusRepository = $this->getDoctrine()->getRepository(UserStatus::class);
            $defaultStatus = $statusRepository->findOneBy(['name' => 'active']);
            $user->setStatus($defaultStatus);

            $encoder = $this->get('security.password_encoder');

            $user->setPassword($encoder->encodePassword($user, $user->getPlainPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render('user/register.html.twig', ['formUser' => $form->createView()]);
    }

    /**
     * @Route("/user/orders", name="user_orders")
     */
    public function listOrders()
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('user_login');
        }

        $orders = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->findBy(['user' => $user]);

        return $this->render('user/orders.html.twig', ['orders' => $orders]);
    }
}
