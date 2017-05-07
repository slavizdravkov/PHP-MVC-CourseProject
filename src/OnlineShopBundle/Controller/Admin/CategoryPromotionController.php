<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\CategoryPromotion;
use OnlineShopBundle\Form\CategoryPromotionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryPromotionController extends Controller
{
    /**
     * @Route("admin/category-promotion/create", name="category_promotion_create")
     * @Method("GET")
     *
     * @return Response
     */
    public function createPromotion()
    {
        $createPromotionForm = $this->createForm(CategoryPromotionType::class);

        return $this->render('admin/promotions/categories-promotion/create.html.twig',
            array('createPromotionForm' => $createPromotionForm->createView()));
    }

    /**
     * @Route("admin/category-promotion/create", name="category_promotion_create_process")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createPromotionProcess(Request $request)
    {
        $categoryPromotion = new CategoryPromotion();
        $form = $this->createForm(CategoryPromotionType::class, $categoryPromotion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoryPromotion);
            $em->flush();

            $this->addFlash('success', "Промоцията е добавена");

            return $this->redirectToRoute('category_promotion_list');

        }

        return $this->render('admin/promotions/categories-promotion/create.html.twig',
            array('createPromotionForm' => $form->createView()));
    }

    /**
     * @Route("admin/category-promotion/list", name="category_promotion_list")
     */
    public function listCategoryPromotions()
    {
        $categoryPromotions = $this->getDoctrine()
            ->getRepository(CategoryPromotion::class)
            ->findAll();

        return $this->render('admin/promotions/categories-promotion/list.html.twig',
            array('promotions' => $categoryPromotions));
    }

    /**
     * @Route("admin/category-promotion/add-category/{id}", name="category_promotion_add_category")
     *
     * @param Request $request
     * @param CategoryPromotion $promotion
     *
     * @return Response
     */
    public function addCategoryPromotions(Request $request, CategoryPromotion $promotion)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        if ($request->isMethod('POST')) {

            $promoCategories = $request->get('categories');

            foreach ($promoCategories as $id_category) {
                $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->find($id_category);

                if ($category) {
                    $promotion->addCategory($category);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirectToRoute('category_promotion_list');
        }
        return $this->render('admin/promotions/categories-promotion/addcategory.html.twig',
            [
                'promotion' => $promotion,
                'categories' => $categories
            ]);
    }

}
