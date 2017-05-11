<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        $promotionManager = $this->get('promotion_manager');
        $categoriesInPromotion = $promotionManager->getCategoriesInPromotion();

        $calc = $this->get('price_calculator');
//        dump($promotionalProducts);
//        die;
        return $this->render('shop/index.html.twig',
            [
                'categories' => $categories,
                'products' => $pagination,
                'calc' => $calc,
                'categoriesInPromotion' => $categoriesInPromotion
            ]);
    }

}
