<?php

namespace OnlineShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cart
 *
 * @ORM\Table(name="carts")
 * @ORM\Entity(repositoryClass="OnlineShopBundle\Repository\CartRepository")
 */
class Cart
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="OnlineShopBundle\Entity\User", inversedBy="carts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_first_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     minMessage="Минималния брой символи трябва да бъде 3"
     * )
     */
    private $shipFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_last_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     minMessage="Минималния брой символи трябва да бъде 3"
     * )
     */
    private $shipLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_city", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="3",
     *     minMessage="Минималния брой символи трябва да бъде 3"
     * )
     */
    private $shipCity;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_zip_code", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $shipZipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_address", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $shipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="ship_email", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Email(message="Въведения мейл адрес {{ value }} не е валиден")
     */
    private $shipEmail;


    /**
     * @var string
     *
     * @ORM\Column(name="ship_phone", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $shipPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_method", type="string", length=255)
     */
    private $paymentMethod;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @var CartProduct[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OnlineShopBundle\Entity\CartProduct", mappedBy="cart")
     */
    private $cartProduct;

    public function __construct($shipFirstName, $shipLastName, $shipCity, $shipAddress, $shipEmail)
    {
        $this->shipFirstName = $shipFirstName;
        $this->shipLastName = $shipLastName;
        $this->shipCity = $shipCity;
        $this->shipZipCode = "";
        $this->shipAddress = $shipAddress;
        $this->shipEmail = $shipEmail;
        $this->shipPhone = "";
        $this->amount = 0.00;
        $this->paymentMethod = "";
        $this->cartProduct = new ArrayCollection();
    }



//    public function __construct()
//    {
//        $this->amount = 0;
//        $this->address = "";
//    }


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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Cart
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getShipAddress()
    {
        return $this->shipAddress;
    }

    /**
     * @param string $shipAddress
     */
    public function setShipAddress(string $shipAddress)
    {
        $this->shipAddress = $shipAddress;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Cart
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     *
     * @return Cart
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return string
     */
    public function getShipFirstName()
    {
        return $this->shipFirstName;
    }

    /**
     * @param string $shipFirstName
     */
    public function setShipFirstName(string $shipFirstName)
    {
        $this->shipFirstName = $shipFirstName;
    }

    /**
     * @return string
     */
    public function getShipLastName()
    {
        return $this->shipLastName;
    }

    /**
     * @param string $shipLastName
     */
    public function setShipLastName(string $shipLastName)
    {
        $this->shipLastName = $shipLastName;
    }

    /**
     * @return string
     */
    public function getShipCity()
    {
        return $this->shipCity;
    }

    /**
     * @param string $shipCity
     */
    public function setShipCity(string $shipCity)
    {
        $this->shipCity = $shipCity;
    }

    /**
     * @return string
     */
    public function getShipZipCode()
    {
        return $this->shipZipCode;
    }

    /**
     * @param string $shipZipCode
     */
    public function setShipZipCode(string $shipZipCode)
    {
        $this->shipZipCode = $shipZipCode;
    }

    /**
     * @return string
     */
    public function getShipEmail()
    {
        return $this->shipEmail;
    }

    /**
     * @param string $shipEmail
     */
    public function setShipEmail(string $shipEmail)
    {
        $this->shipEmail = $shipEmail;
    }

    /**
     * @return string
     */
    public function getShipPhone()
    {
        return $this->shipPhone;
    }

    /**
     * @param string $shipPhone
     */
    public function setShipPhone(string $shipPhone)
    {
        $this->shipPhone = $shipPhone;
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return ArrayCollection|CartProduct[]
     */
    public function getCartProduct()
    {
        return $this->cartProduct;
    }

    /**
     * @param ArrayCollection|CartProduct[] $cartProduct
     */
    public function setCartProduct($cartProduct)
    {
        $this->cartProduct = $cartProduct;
    }
}

