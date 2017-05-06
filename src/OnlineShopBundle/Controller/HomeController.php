<?php

namespace OnlineShopBundle\Controller;

use OnlineShopBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy([], ['name' => 'ASC']);
        $promotionManager = $this->get('promotion_manager');
        $promotionalProducts = $promotionManager->getPromotionalProducts();
        $calc = $this->get('price_calculator');
//        dump($promotionalProducts);
//        die;
        return $this->render('shop/index.html.twig',
            [
                'categories' => $categories,
                'products' => $promotionalProducts,
                'calc' => $calc
            ]);
    }

}
