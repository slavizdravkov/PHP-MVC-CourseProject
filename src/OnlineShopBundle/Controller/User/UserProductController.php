<?php

namespace OnlineShopBundle\Controller\User;

use OnlineShopBundle\Entity\Cart;
use OnlineShopBundle\Entity\CartProduct;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\User;
use OnlineShopBundle\Entity\UserProduct;
use OnlineShopBundle\Form\UserProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserProductController extends Controller
{
    /**
     * @Route("/user/products/list", name="user_products_list")
     */
    public function listProducts()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user === null) {
            return $this->redirectToRoute('homepage');
        }

        $userProducts = $this->getUserProducts($user);

//        dump($userProducts);
//        die;

        return $this->render('user/products/list.html.twig',
            [
                'userProducts' => $userProducts
            ]);
    }

    /**
     * @Route("/user/products/sell/{id}", name="user_products_sell")
     *
     * @param Product $product
     * @param Request $request
     */
    public function sellProduct(Request $request, Product $product)
    {
        if ($product === null) {
            return $this->redirectToRoute('user_products_list');
        }

        $userProduct = new UserProduct();
        $userProduct->setProperties($product);

        $sellProductForm = $this->createForm(UserProductType::class, $userProduct);
        $sellProductForm->handleRequest($request);

        if ($sellProductForm->isSubmitted() && $sellProductForm->isValid()) {

            $userProduct->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($userProduct);
            $em->flush();

            return $this->redirectToRoute('user_sell_products_list');
        }
        return $this->render('user/products/add.html.twig',
            [
                'sellForm' => $sellProductForm->createView()
            ]);
    }

    /**
     * @Route("/user/sell-products/list", name="user_sell_products_list")
     */
    public function sellProductsList()
    {
        $sellProducts = $this->getDoctrine()->getRepository(UserProduct::class)->findAll();

        return $this->render('user/products/sell-products/list.html.twig', ['sellProducts' => $sellProducts]);
    }

    /**
     * @param User $user
     *
     * @return Product[]
     */
    private function getUserProducts($user)
    {
        /** @var Cart[] $userOrders */
        $userOrders = $user->getCarts()->toArray();

        $purchasedProducts = [];

        foreach ($userOrders as $userOrder) {

            /** @var CartProduct[] $cartProducts */
            $cartProducts = $userOrder->getCartProduct()->toArray();

            foreach ($cartProducts as $cartProduct) {

                if (!array_key_exists($cartProduct->getProduct()->getId(), $purchasedProducts)) {
                    $purchasedProducts[$cartProduct->getProduct()->getId()] = $cartProduct->getProduct();

                }
            }
        }

        return $purchasedProducts;
    }
}
