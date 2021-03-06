<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/category/{id}", name="products_by_category")
     * @param $id
     *
     * @return Response
     */
    public function listArticles($id, Request $request)
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findProductByCategory($category);

        $promotionManager = $this->get('promotion_manager');
        $categoriesInPromotion = $promotionManager->getCategoriesInPromotion();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $products,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

//        /** @var Product[]|UserProduct[] $products */
//        $products = $category->getProducts()->toArray();
//
//        usort($products, function ($a, $b){
//            return $a->getPrice() > $b->getPrice();
//        });

        $calc = $this->get('price_calculator');

        return $this->render
        ('shop/products.html.twig',
            [
                'category' => $category,
                'products' => $pagination,
                'categories' => $categories,
                'calc' => $calc,
                'categoriesInPromotion' => $categoriesInPromotion
            ]
        );
    }

    /**
     * @Route("/product/view/{id}", name="view_product")
     *
     * @param Product $product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productView(Product $product)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        //$product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $calc = $this->get('price_calculator');

        $promotionManager = $this->get('promotion_manager');
        $categoriesInPromotion = $promotionManager->getCategoriesInPromotion();


        return $this->render('shop/viewproduct.html.twig',
            [
                'product' => $product,
                'categories' => $categories,
                'calc' => $calc,
                'categoriesInPromotion' => $categoriesInPromotion
            ]);
    }

}
