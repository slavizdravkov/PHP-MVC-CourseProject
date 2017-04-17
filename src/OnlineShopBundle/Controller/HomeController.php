<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy([], ['name' => 'ASC']);

        return $this->render('shop/index.html.twig', ['categories' => $categories]);
    }

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
}
