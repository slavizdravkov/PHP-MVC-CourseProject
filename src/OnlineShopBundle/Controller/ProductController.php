<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/category/{id}", name="products_by_category")
     * @param $id
     *
     * @return Response
     */
    public function listArticles($id)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        /** @var Product[] $products */
        $products = $category->getProducts()->toArray();
        usort($products, function ($a, $b){
            return $a->getPrice() > $b->getPrice();
        });

        return $this->render
        ('shop/products.html.twig',
            [
                'category' => $category,
                'products' => $products,
                'categories' => $categories
            ]
        );
    }

    /**
     * @Route("/product/view/{id}", name="view_product")
     *
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productView($id)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        return $this->render('shop/viewproduct.html.twig', ['product' => $product, 'categories' => $categories]);
    }

}
