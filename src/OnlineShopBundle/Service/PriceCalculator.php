<?php

namespace OnlineShopBundle\Service;


use OnlineShopBundle\Entity\Product;

class PriceCalculator
{
    /** @var  PromotionManager */
    protected $manager;

    public function __construct(PromotionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Product $product
     *
     * @return float
     */
    public function Calculate($product)
    {
        $promotionalProducts = $this->manager->getProductsDiscount();
        $discount = 0;
        if (array_key_exists($product->getId(), $promotionalProducts)) {
            $discount = $promotionalProducts[$product->getId()];
        }

        return $product->getPrice() - $product->getPrice() * ($discount / 100);
    }
}