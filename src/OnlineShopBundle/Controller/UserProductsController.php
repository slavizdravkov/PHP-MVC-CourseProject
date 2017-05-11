<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\UserProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserProductsController extends Controller
{
    /**
     * @Route("/userproducts/list", name="userproducts_list")
     */
    public function listUserProducts()
    {
        $userProducts = $this->getDoctrine()->getRepository(UserProduct::class)->findAll();

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $promotionManager = $this->get('promotion_manager');
        $categoriesInPromotion = $promotionManager->getCategoriesInPromotion();

        $categories = $categoryRepository->findAll();
        //$category = $categoryRepository->findOneBy(['name' => 'Продукти на потребители']);

        return $this->render('shop/userproducts/list.html.twig',
            [
                'products' => $userProducts,
                'categories' => $categories,
                'categoriesInPromotion' => $categoriesInPromotion
            ]);
    }

    /**
     * @Route("/userproducts/view/{id}", name="userproducts_view")
     *
     * @param UserProduct $userProduct
     *
     * @return Response
     */
    public function viewUserProduct(UserProduct $userProduct)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $promotionManager = $this->get('promotion_manager');
        $categoriesInPromotion = $promotionManager->getCategoriesInPromotion();

        return $this->render('shop/userproducts/viewproduct.html.twig',
            [
                'product' => $userProduct,
                'categories' => $categories,
                'categoriesInPromotion' => $categoriesInPromotion
            ]);
    }
}
