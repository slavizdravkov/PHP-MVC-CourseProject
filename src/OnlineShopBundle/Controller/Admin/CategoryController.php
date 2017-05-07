<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @Route("/admin/category/create", name="category_create")
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
     * @Route("/admin/category/create", name="category_create_process")
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

            return $this->redirectToRoute('categories_list');
        }

        return $this->render
        (
            'admin/categories/create.html.twig',
                [
                    'categoryForm' => $form->createView(),
                ]
        );
    }

    /**
     * @Route("/admin/categories/list", name="categories_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCategories()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);

        return $this->render('admin/categories/list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/admin/categories/edit/{id}", name="categories_edit")
     *
     * @param Request $request
     * @param Category $category
     *
     * @return Response
     */
    public function editCategories(Request $request, Category $category)
    {
        if ($category === null) {
            return $this->redirectToRoute('categories_list');
        }

        $editForm = $this->createForm(CategoryType::class, $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Категорията е редактирана");

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/categories/edit.html.twig',
            [
                'category' => $category,
                'editForm' => $editForm->createView()
            ]);
    }

    /**
     * @Route("/admin/categories/delete/{id}", name="categories_delete")
     *
     * @param Request $request
     * @param Category $category
     *
     * @return Response
     */
    public function deleteCategory(Request $request, Category $category)
    {
        if ($category === null) {
            return $this->redirectToRoute('categories_list');
        }

        $deleteForm = $this->createForm(CategoryType::class, $category);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() &&  $deleteForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            return $this->redirectToRoute('categories_list');
        }

        return $this->render('admin/categories/delete.html.twig',
            [
               'category' => $category,
                'deleteForm' => $deleteForm->createView()
            ]);
    }
}
