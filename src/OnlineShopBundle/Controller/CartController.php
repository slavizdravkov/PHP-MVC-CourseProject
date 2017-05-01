<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Cart;
use OnlineShopBundle\Entity\CartProduct;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\User;
use OnlineShopBundle\Form\CartCheckoutType;
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
                'total' => number_format($totalSum, 2),
                'cart' => $cart
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
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('user_login');
        }

        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        $cart_id = $session->get('cart_id', false);

        if (!$cart_id) {
            $cart = new Cart(
                $user->getFirstName(),
                $user->getLastName(),
                $user->getCity()->getName(),
                $user->getAddress(),
                $user->getEmail()
            );
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
        $session = $this->get('session');
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

        $cart = $this->getDoctrine()
            ->getRepository(Cart::class)
            ->find($session->get('cart_id', false));


        $cart->setDateUpdated(new \DateTime());

        $this->addFlash('success', "Количеството е актуализирано");

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete_product")
     *
     * @param CartProduct $cartProduct
     *
     * @return RedirectResponse
     */
    public function deleteAction(CartProduct $cartProduct)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cartProduct);
        $em->flush();

        $this->addFlash('success', "Продуктът е изтрит");

        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/cart/checkout/{id}", name="cart_checkout")
     *
     * @param Cart $cart
     * @param Request $request
     *
     * @return Response
     */
    public function checkoutAction(Request $request, Cart $cart)
    {
        $form = $this->createForm(CartCheckoutType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cart->setPaymentMethod($request->get('payment'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();

            return $this->redirectToRoute('cart_order');

        }

        return $this->render('cart/checkout.html.twig',
            [
                'cart' => $cart,
                'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/cart/order", name="cart_order")
     */
    public function orderAction()
    {
        $session = $this->get('session');
        $cart_id = $session->get('cart_id', false);

        if (!$cart_id) {
            return $this->redirectToRoute('homepage');
        }

        $cart = $this->getDoctrine()->getRepository(Cart::class)->find($cart_id);

        $productsInCart = $this->getDoctrine()
            ->getRepository(CartProduct::class)
            ->findBy(['cart' => $cart]);


        return $this->render('cart/order.html.twig', [
            'cart' => $cart,
            'cart_products' => $productsInCart
        ]);
    }
}
