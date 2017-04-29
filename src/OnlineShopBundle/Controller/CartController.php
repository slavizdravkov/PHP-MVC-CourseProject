<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Cart;
use OnlineShopBundle\Entity\CartProduct;
use OnlineShopBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function indexAction()
    {
        $session = $this->get('session');

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($session->get('cart_id', false));


        $productsInCart = $this->getDoctrine()
            ->getRepository(CartProduct::class)
            ->findBy(['cart' => $cart]);

        $totalSum = 0.00;
        foreach ($productsInCart as $product) {
            $totalSum += $product->getProductPrice();
        }



        return $this->render('cart/add.html.twig',
            [
                'cart_products' => $productsInCart,
                'total' => number_format($totalSum, 2)
            ]);

    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     *
     * @param Request $request
     * @param Product $product
     *
     * @return Response
     */
    public function addAction(Request $request, Product $product)
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('user_login');
        }

        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        $cart_id = $session->get('cart_id', false);

        if (!$cart_id) {
            $cart = new Cart();
            $cart->setUser($user);
            $cart->setDateCreated(new \DateTime());
            $cart->setDateUpdated(new \DateTime());

            $em->persist($cart);
            $em->flush();

            $session->set('cart_id', $cart->getId());
        }

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($session->get('cart_id', false));

        if ($product) {

            $cartProduct = $this->getDoctrine()
                ->getRepository(CartProduct::class)
                ->findOneBy(['cart' => $cart, 'product' => $product]);

            if (!$cartProduct) {
                $cartProduct = new CartProduct();
                $cartProduct->setCart($cart);
                $cartProduct->setProduct($product);
                $cartProduct->setQty(1);
                $cartProduct->setProductPrice($product->getPrice());
            } else {
                $cartProduct->setQty($cartProduct->getQty() + 1);
                $cartProduct->setProductPrice($product->getPrice() * (float)$cartProduct->getQty());
            }

            $em->persist($cartProduct);
        }

        $cart->setDateUpdated(new \DateTime());

        $em->persist($cart);

        $em->flush();

        return $this->redirectToRoute('cart_index');

    }

    /**
     * @Route("cart/update", name="cart_update")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qty = $request->get('quantity');

        foreach ($qty as $id => $quantity) {
            $cartProduct = $this->getDoctrine()
                ->getRepository(CartProduct::class)
                ->findOneBy(['id' => $id]);

            if ($cartProduct) {
                $cartProduct->setQty($quantity);
                $cartProduct->setProductPrice($cartProduct->getProduct()->getPrice() * (float)$cartProduct->getQty());
                $em->persist($cartProduct);
                $em->flush();
            }
        }

        return $this->redirectToRoute('cart_index');
    }
}
