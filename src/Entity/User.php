<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Entity User
 *
 * @ORM\Table
 * @ORM\Entity
 * @UniqueEntity("email")
 */
class User implements UserInterface, \Serializable
{
    use Timestampable;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer", unique=true, options={"unsigned"=true})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\Regex("/^([a-zA-Z0-9_-]+\.)*[a-zA-Z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/")
     *
     * @ORM\Column(type="string", length=128)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\Choice({"ROLE_SUPER_ADMIN", "ROLE_MANAGER", "ROLE_USER"})
     *
     * @ORM\Column(type="string", length=32)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $apiToken;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="managers")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $apiToken
     *
     * @return $this
     */
    public function setApiToken(string $apiToken)
    {
        $this->apiToken = $apiToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param string $role
     *
     * @return $this
     */
    public function setRole(string $role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param Category $category
     */
    public function addCategory(Category $category)
    {
        if (!$this->getCategories()->contains($category)) {
            $this->categories->add($category);
        }
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials()
    {
        // @todo
    }

    public function getSalt()
    {
        // @todo
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
        ]);
    }

    public function unserialize($string)
    {
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            ) = unserialize($string, ['allowed_classes' => false]);
    }
}
