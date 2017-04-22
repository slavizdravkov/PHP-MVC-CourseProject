<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("/create/product", name="create_product")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProduct()
    {
        $form = $this->createForm(ProductType::class);

        return $this->render
        (
            'admin/products/create.html.twig',
                [
                    'productForm' => $form->createView()
                ]
        );
    }

    /**
     * @Route("/create/product", name="create_product_process")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProductProcess(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', "Продуктът е добавен");

            return $this->redirectToRoute('create_product');

        }

        return $this->render
        (
            'admin/products/create.html.twig',
            [
                'productForm' => $form->createView()
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

        return $this->render('product/view.html.twig', ['product' => $product, 'categories' => $categories]);
    }
}
