<?php

namespace OnlineShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProductPromotion
 *
 * @ORM\Table(name="promotions_for_products")
 * @ORM\Entity(repositoryClass="OnlineShopBundle\Repository\ProductPromotionRepository")
 */
class ProductPromotion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="percent", type="integer")
     */
    private $percent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_data", type="datetime")
     */
    private $startData;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_data", type="datetime")
     */
    private $endData;

    /**
     * @var Product[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="OnlineShopBundle\Entity\Product")
     * @ORM\JoinTable(name="promotions_products",
     *     joinColumns={@ORM\JoinColumn(name="promotion_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *     )
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set percent
     *
     * @param integer $percent
     *
     * @return ProductPromotion
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set startData
     *
     * @param \DateTime $startData
     *
     * @return ProductPromotion
     */
    public function setStartData($startData)
    {
        $this->startData = $startData;

        return $this;
    }

    /**
     * Get startData
     *
     * @return \DateTime
     */
    public function getStartData()
    {
        return $this->startData;
    }

    /**
     * Set endData
     *
     * @param \DateTime $endData
     *
     * @return ProductPromotion
     */
    public function setEndData($endData)
    {
        $this->endData = $endData;

        return $this;
    }

    /**
     * Get endData
     *
     * @return \DateTime
     */
    public function getEndData()
    {
        return $this->endData;
    }

    /**
     * @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection|Product[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @param Product $product
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
    }

}

