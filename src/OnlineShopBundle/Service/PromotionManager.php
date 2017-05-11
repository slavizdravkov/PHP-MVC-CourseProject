<?php

namespace OnlineShopBundle\Service;


use OnlineShopBundle\Entity\Category;
use OnlineShopBundle\Entity\CategoryPromotion;
use OnlineShopBundle\Entity\Product;
use OnlineShopBundle\Entity\ProductPromotion;
use OnlineShopBundle\Repository\CategoryPromotionRepository;
use OnlineShopBundle\Repository\ProductPromotionRepository;

class PromotionManager
{
    /**
     * Масив с всички активни промоции на продукти
     *
     * @var ProductPromotion[]
     */
    protected $product_promotions;

    /**
     * Масив с всички активни промоции на категории
     *
     * @var CategoryPromotion[]
     */
    protected $category_promotions;

    /**
     * Масив с всички промоционални продукти
     *
     * @var Product[]
     */
    protected $promotional_products;

    /**
     * Асоциативен масив ProductID => отстъпка
     *
     * @var array
     */
    protected $products_discount;

    /**
     * Отстъпката, ако има активирана промоция на всички продукти
     *
     * @var integer
     */
    protected $all_product_discount;

    public function __construct(ProductPromotionRepository $productPromotionRepository,
                                CategoryPromotionRepository $categoryPromotionRepository)
    {
        $this->product_promotions = $productPromotionRepository->fetchActiveProductPromotions();
        $this->category_promotions = $categoryPromotionRepository->fetchActiveCategoryPromotions();
        $this->products_discount = $this->getProductsWithPromotions();
    }

    /**
     * @return bool
     */
    public function hasAllProductPromotion()
    {

        foreach ($this->product_promotions as $promotion) {

            $productsInPromotion = $promotion->getProducts()->count();

            if ($productsInPromotion === 0) {

                $this->all_product_discount = $promotion->getPercent();

                return true;
            }


        }

        return false;
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

    /**
     * @return int
     */
    public function getAllProductDiscount()
    {
        return $this->all_product_discount;
    }

    /**
     * @return CategoryPromotion[]
     */
    public function getCategoryPromotions()
    {
        return $this->category_promotions;
    }


    /**
     * Връща промоцията за съответната категория
     *
     * @param Category $category
     *
     * @return integer
     */
    public function categoryPromotion($category)
    {
        $activePromotions = $this->getCategoryPromotions();

        foreach ($activePromotions as $activePromotion) {

            $promotionalCategories = $activePromotion->getCategories();

            foreach ($promotionalCategories as $promotionalCategory) {

                if ($promotionalCategory->getId() === $category->getId()) {
                    return $activePromotion->getPercent();
                }
            }
        }

        return 0;
    }

    /**
     * Връща всички категории в промоция
     *
     * @return Category[] array
     */
    public function getCategoriesInPromotion()
    {
        $categoriesInPromotion = [];

        foreach ($this->getCategoryPromotions() as $categoryPromotion) {
            $categories = $categoryPromotion->getCategories();

            foreach ($categories as $category) {
                if (!array_key_exists($category->getId(), $categoriesInPromotion)) {
                    $categoriesInPromotion[$category->getId()] = $category;
                }
            }
        }

        return $categoriesInPromotion;
    }
}