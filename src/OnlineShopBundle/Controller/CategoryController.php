<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/create/category", name="create_category")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCategory()
    {
        $form = $this->createForm(CategoryType::class);

        return $this->render
        (
            'admin/categories/create.html.twig',
                [
                    'categoryForm' => $form->createView(),
                ]
        );
    }


    /**
     * @Route("/create/category", name="create_category_process")
     * @Method("POST")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCategoryProcess(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', "Категорията е добавена");

            return $this->redirectToRoute('create_category');
        }

        return $this->render
        (
            'admin/categories/create.html.twig',
                [
                    'categoryForm' => $form->createView(),
                ]
        );
    }
}
