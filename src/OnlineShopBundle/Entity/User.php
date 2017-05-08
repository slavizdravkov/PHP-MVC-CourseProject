<?php

namespace OnlineShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="OnlineShopBundle\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     *
     * @Assert\NotBlank()
     * @Assert\Email(message="Въведения мейл адрес {{ value }} не е валиден")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="3")
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="OnlineShopBundle\Entity\City", inversedBy="users")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", onDelete="SET NULL")
     * @Assert\NotBlank()
     *
     * @var City
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text")
     *
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @ORM\ManyToOne(targetEntity="OnlineShopBundle\Entity\UserStatus", inversedBy="users")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var UserStatus
     */
    private $status;

    /**
     * @var Role[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="OnlineShopBundle\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")})
     */
    private $roles;

    /**
     * @var int
     *
     *@ORM\Column(name="cash", type="integer")
     */
    private $cash;

    /**
     * @var Cart[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OnlineShopBundle\Entity\Cart", mappedBy="user")
     */
    private $carts;

    /**
     * @var UserProduct[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="OnlineShopBundle\Entity\UserProduct", mappedBy="user")
     */
    private $userProducts;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->carts = new ArrayCollection();
        $this->userProducts = new ArrayCollection();
        $this->cash = 500;
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }


    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $rolesAsStringArray = [];

        foreach ($this->roles as $role) {
            /** @var Role $role */
            $rolesAsStringArray[] = is_string($role) ? $role : $role->getRole();
        }

        return $rolesAsStringArray;
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @param ArrayCollection|Role[] $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return UserStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param UserStatus $status
     */
    public function setStatus(UserStatus $status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param int $cash
     */
    public function setCash(int $cash)
    {
        $this->cash = $cash;
    }

    /**
     * @return ArrayCollection|Cart[]
     */
    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * @param ArrayCollection|Cart[] $carts
     */
    public function setCarts($carts)
    {
        $this->carts = $carts;
    }

    /**
     * @return ArrayCollection|UserProduct[]
     */
    public function getUserProducts()
    {
        return $this->userProducts;
    }

    /**
     * @param ArrayCollection|UserProduct[] $userProducts
     */
    public function setUserProducts($userProducts)
    {
        $this->userProducts = $userProducts;
    }

    public function isAdmin()
    {
        return in_array("ROLE_ADMIN", $this->getRoles());
    }

    public function isEditor()
    {
        return in_array("ROLE_EDITOR", $this->getRoles());
    }

    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getRolesAsString()
    {
        $rolesArray =
            [
                'ROLE_USER' => 'User',
                'ROLE_EDITOR' => 'Editor',
                'ROLE_ADMIN' => 'Admin'
            ];
        $roleNames = [];
        foreach ($this->getRoles() as $role) {
            if (array_key_exists($role, $rolesArray)) {
                $roleNames[] = $rolesArray[$role];
            }
        }
        return implode(", ", $roleNames);
    }

}

