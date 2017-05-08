<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\UserProduct;
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

        /** @var Product[]|UserProduct[] $products */
        $products = $category->getProducts()->toArray();
        usort($products, function ($a, $b){
            return $a->getPrice() > $b->getPrice();
        });

        $calc = $this->get('price_calculator');

        return $this->render
        ('shop/products.html.twig',
            [
                'category' => $category,
                'products' => $products,
                'categories' => $categories,
                'calc' => $calc
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

        return $this->render('shop/viewproduct.html.twig',
            [
                'product' => $product,
                'categories' => $categories,
                'calc' => $calc
            ]);
    }

}
