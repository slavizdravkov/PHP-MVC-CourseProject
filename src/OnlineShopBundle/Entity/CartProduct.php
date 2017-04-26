<?php

namespace OnlineShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartProduct
 *
 * @ORM\Table(name="carts_products")
 * @ORM\Entity(repositoryClass="OnlineShopBundle\Repository\CartProductRepository")
 */
class CartProduct
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
     * @var Cart
     *
     * @ORM\ManyToOne(targetEntity="OnlineShopBundle\Entity\Cart")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cartId;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="OnlineShopBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="integer")
     */
    private $qty;

    /**
     * @var string
     *
     * @ORM\Column(name="product_price", type="decimal", precision=10, scale=2)
     */
    private $productPrice;


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
     * Set cartId
     *
     * @param integer $cartId
     *
     * @return CartProduct
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;

        return $this;
    }

    /**
     * Get cartId
     *
     * @return int
     */
    public function getCartId()
    {
        return $this->cartId;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return CartProduct
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set qty
     *
     * @param integer $qty
     *
     * @return CartProduct
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get qty
     *
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * Set productPrice
     *
     * @param string $productPrice
     *
     * @return CartProduct
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    /**
     * Get productPrice
     *
     * @return string
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }
}

