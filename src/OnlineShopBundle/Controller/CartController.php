<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Cart;
use OnlineShopBundle\Entity\CartProduct;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\User;
use OnlineShopBundle\Entity\UserProduct;
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

        $total = $this->totalSum($productsInCart);

        $calc = $this->get('price_calculator');

        return $this->render('cart/add.html.twig',
            [
                'cart_products' => $productsInCart,
                'total' => $total,
                'cart' => $cart,
                'calc' => $calc
            ]);

    }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     *
     * @param $id
     *
     * @return Response
     */
    public function addAction($id)
    {
        /** @var User $user */
        $user = $this->getUser();

        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$product) {
            $product = $this->getDoctrine()->getRepository(UserProduct::class)->find($id);
        }

        $calc = $this->get('price_calculator');

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
                if ($product instanceof Product) {
                    $cartProduct->setProduct($product);
                } else {
                    $cartProduct->setUserProduct($product);
                }
                $cartProduct->setQty(1);
                $cartProduct->setProductPrice($calc->Calculate($product));
            } else {
                $cartProduct->setQty($cartProduct->getQty() + 1);
                $cartProduct->setProductPrice($calc->Calculate($product) * (float)$cartProduct->getQty());
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

        $calc = $this->get('price_calculator');

        foreach ($qty as $id => $quantity) {
            $cartProduct = $this->getDoctrine()
                ->getRepository(CartProduct::class)
                ->findOneBy(['id' => $id]);

            if ($cartProduct) {
                $cartProduct->setQty($quantity);
                if ($cartProduct->getProduct()) {
                    $cartProduct
                        ->setProductPrice(round($calc->Calculate($cartProduct->getProduct()), 2) * $cartProduct->getQty());
                } else {
                    $cartProduct
                        ->setProductPrice(round($calc->Calculate($cartProduct->getUserProduct()), 2) * $cartProduct->getQty());
                }
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
     *
     * @param Request $request
     *
     * @return Response
     */
    public function orderAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $session = $this->get('session');

        $cart_id = $session->get('cart_id', false);

        $calc = $this->get('price_calculator');

        if (!$cart_id) {
            return $this->redirectToRoute('homepage');
        }

        $cart = $this->getDoctrine()->getRepository(Cart::class)->find($cart_id);

        $productsInCart = $this->getDoctrine()
            ->getRepository(CartProduct::class)
            ->findBy(['cart' => $cart]);

        $total = $this->totalSum($productsInCart);

        if ($request->isMethod('POST')){
            $cart->setAmount($total);
            $cart->setDateUpdated(new \DateTime());
            $user->setCash($user->getCash() - $total);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->persist($cart);
            $em->flush();

            $session->remove('cart_id');

            return $this->redirectToRoute('user_orders');
        }

        return $this->render('cart/order.html.twig', [
            'cart' => $cart,
            'cart_products' => $productsInCart,
            'total' => $total,
            'calc' => $calc
        ]);
    }

    /**
     * @param CartProduct $cartProduct
     *
     * @return string
     */
    public function totalSum($cartProduct)
    {
        $totalSum = 0.00;
        foreach ($cartProduct as $product) {
            $totalSum += $product->getProductPrice();
        }

        return number_format($totalSum, 2);
    }
}
