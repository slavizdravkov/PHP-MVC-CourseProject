<?php

namespace OnlineShopBundle\Controller\Admin;

use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    const FILE_NAME = 'd385aca08b4a5437580b3c6e91584fbc.jpeg';

    /**
     * @Route("/admin/create/product", name="create_product")
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
     * @Route("/admin/create/product", name="create_product_process")
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


        if ($form->isValid() && $form->isSubmitted()) {

            $fileName = self::FILE_NAME;

            $file = $product->getImageUrl();

            if ($file) {
                $fileName = $this->get('app.image_uploader')->upload($file);
            }

            $product->setImageUrl($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', "Продуктът е добавен");

            return $this->redirectToRoute('products_list');

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
     *
     * @Route("/admin/products/list", name="products_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listProducts()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('admin/products/list.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="product_edit")
     *
     * @param Request $request
     * @param $id
     *
     * @return Response
     */
    public function editProduct(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $oldImageName = $product->getImageUrl();

//        $product->setImageUrl(
//            new File($this->getParameter('images_directory') . '/' . $product->getImageUrl()));

        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if ($product->getImageUrl() instanceof UploadedFile) {
                $file = $product->getImageUrl();
                $fileName = $this->get('app.image_uploader')->upload($file);

                if ($oldImageName != self::FILE_NAME) {

                    $this->get('app.image_uploader')->remove($oldImageName);
                }

                $product->setImageUrl($fileName);
            } else {
                $product->setImageUrl($oldImageName);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Продуктът е редактиран");

            return $this->redirectToRoute('products_list');
        }

        return $this->render('admin/products/edit.html.twig',
            [
                'product' => $product,
                'editForm' => $editForm->createView()
            ]);
    }

    /**
     * @Route("/admin/product/delete/{id}", name="product_delete")
     *
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function deleteProduct(Request $request, Product $product)
    {
        $imageName = $product->getImageUrl();
        $deleteForm = $this->createForm(ProductType::class, $product);
        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            if ($imageName != self::FILE_NAME) {
                $this->get('app.image_uploader')->remove($imageName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();

            return $this->redirectToRoute('products_list');
        }

        return $this->render('admin/products/delete.html.twig', ['deleteForm' => $deleteForm->createView()]);
    }

}
