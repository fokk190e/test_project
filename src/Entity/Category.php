<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * Entity Category
 *
 * @ORM\Table
 * @ORM\Entity
 */
class Category
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", unique=true, options={"unsigned"=true})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category", cascade={"persist", "remove"} )
     */
    private $products;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="allowedCategories")
     */
    private $managers;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        if (!$this->getProducts()->contains($product)) {
            $this->products->add($product);
        }
    }

    /**
     * @return ArrayCollection|Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param User $user
     */
    public function addManager(User $user)
    {
        if (!$this->getManagers()->contains($user)) {
            $this->managers->add($user);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getManagers()
    {
        return $this->managers;
    }
}
