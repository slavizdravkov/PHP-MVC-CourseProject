<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\ProductPromotion;
use OnlineShopBundle\Form\ProductPromotionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductPromotionController extends Controller
{
    /**
     * @Route("admin/products-promotion/create", name="products_promotion_create")
     * @Method("GET")
     *
     * @return Response
     */
    public function createPromotion()
    {
        $createPromotionForm = $this->createForm(ProductPromotionType::class);

        return $this->render('admin/promotions/products-promotion/create.html.twig',
            array('createPromotionForm' => $createPromotionForm->createView()));
    }

    /**
     * @Route("admin/products-promotion/create", name="products_promotion_create_process")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createPromotionProcess(Request $request)
    {
        $productPromotion = new ProductPromotion();
        $form = $this->createForm(ProductPromotionType::class, $productPromotion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productPromotion);
            $em->flush();

            $this->addFlash('success', "Промоцията е добавена");

            return $this->redirectToRoute('products_promotion_list');

        }

        return $this->render('admin/promotions/products-promotion/create.html.twig',
            array('createPromotionForm' => $form->createView()));
    }

    /**
     * @Route("admin/products-promotion/list", name="products_promotion_list")
     */
    public function listProductPromotions()
    {
        $productPromotions = $this->getDoctrine()
            ->getRepository(ProductPromotion::class)
            ->fetchActiveProductPromotions();

        return $this->render('admin/promotions/products-promotion/list.html.twig',
            array('promotions' => $productPromotions));
    }

    /**
     * @Route("admin/products-promotion/add-product/{id}", name="products_promotion_add_product")
     *
     * @param Request $request
     * @param ProductPromotion $promotion
     *
     * @return Response
     */
    public function addPromotionalProducts(Request $request, ProductPromotion $promotion)
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        if ($request->isMethod('POST')) {

            $products = $request->get('products');

            foreach ($products as $id_product) {
                $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($id_product);

                if ($product) {
                    $promotion->addProduct($product);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            return $this->redirectToRoute('products_promotion_list');
        }
        return $this->render('admin/promotions/products-promotion/addproduct.html.twig',
            [
                'promotion' => $promotion,
                'products' => $products
            ]);
    }

}
