<?php

namespace OnlineShopBundle\Service;


use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\UserProduct;

class PriceCalculator
{
    /** @var  PromotionManager */
    protected $manager;

    public function __construct(PromotionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Product|UserProduct $product
     *
     * @return float
     */
    public function Calculate($product)
    {
        $promotionalProducts = $this->manager->getProductsDiscount();

        $discount = 0;

        if ($this->manager->hasAllProductPromotion()) {
            $discount = $this->manager->getAllProductDiscount();
        }

        if (array_key_exists($product->getId(), $promotionalProducts)) {
            $discount = max($promotionalProducts[$product->getId()], $discount);
        }

        $productCategory = $product->getCategory();

        $discount = max($this->manager->categoryPromotion($productCategory), $discount);

        return $product->getPrice() - $product->getPrice() * ($discount / 100);
    }
}