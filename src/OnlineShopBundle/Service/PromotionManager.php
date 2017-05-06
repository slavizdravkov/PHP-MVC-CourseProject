<?php

namespace OnlineShopBundle\Service;


use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\ProductPromotion;
use OnlineShopBundle\Repository\ProductPromotionRepository;

class PromotionManager
{
    /**
     * @var ProductPromotion[]
     */
    protected $product_promotions;

    protected $category_promotions;

    /**
     * @var Product[]
     */
    protected $promotional_products;

    /**
     * @var array
     */
    protected $products_discount;

    public function __construct(ProductPromotionRepository $repository)
    {
        $this->product_promotions = $repository->fetchActiveProductPromotions();
        $this->products_discount = $this->getProductsWithPromotions();
    }

    /**
     * @return array
     */
    public function getProductsWithPromotions()
    {
        $productPromotions = $this->product_promotions;

        $promotionalProducts = [];

        //$productArray = [];

        foreach ($productPromotions as $productPromotion) {
            /** @var Product[] $products */
            $products = $productPromotion->getProducts();
            $promotion = $productPromotion->getPercent();
            foreach ($products as $product) {
                if (!array_key_exists($product->getId(), $promotionalProducts)) {
                    $promotionalProducts[$product->getId()] = $promotion;
                    $this->addPromotionalProducts($product);
                } else {
                    $maxPromotion = max($promotionalProducts[$product->getId()], $promotion);
                    $promotionalProducts[$product->getId()] = $maxPromotion;
                }
            }
        }

        return $promotionalProducts;
    }

    /**
     * @return Product[]
     */
    public function getPromotionalProducts()
    {
        return $this->promotional_products;
    }

    /**
     * @param Product $product
     */
    public function addPromotionalProducts($product)
    {
        $this->promotional_products[] = $product;
    }

    /**
     * @return array
     */
    public function getProductsDiscount()
    {
        return $this->products_discount;
    }



}