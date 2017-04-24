<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\Role;
use OnlineShopBundle\Entity\User;
use OnlineShopBundle\Form\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/admin/users/edit/{id}", name="users_edit")
     *
     * @param Request $request
     * @param User $user
     *
     * @return Response
     */
    public function editUsers(Request $request, User $user)
    {
        if ($user === null) {
            return $this->redirectToRoute('users_list');
        }
        $originalPassword = $user->getPassword();
        $editForm = $this->createForm(UserEditType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $rolesRequest = $user->getRoles();
            $roleRepository = $this->getDoctrine()->getRepository(Role::class);
            $roles = [];
            foreach ($rolesRequest as $roleName) {
                $roles[] = $roleRepository->findOneBy(['name' => $roleName]);
            }
            $user->setRoles($roles);
            if ($user->getPlainPassword()) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }
            else {
                $user->setPassword($originalPassword);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users_list');

        }
        return $this->render('admin/users/edit.html.twig',
            [
                'user' => $user, 'editForm' => $editForm->createView()
            ]);
    }
}
