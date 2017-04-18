<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="v_users")
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"staff"})
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60)
     *
     * @Groups({"staff"})
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="pass", type="string", length=128)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=254)
     *
     * @Groups({"staff"})
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     *
     * @Groups({"staff"})
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     *
     * @Groups({"staff"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     *
     * @Groups({"staff"})
     */
    private $lastName;

    /**
     * @var \AppBundle\Entity\UserRole
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserRole", mappedBy="user")
     * @Groups({"staff"})
     */
    private $roles;

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return UserRole
     */
    public function getRoles()
    {
        $roles = [];
        if ($this->roles) {
            foreach($this->roles as $role) {
                $roles[] = $role->getRole();
            }
        }

        return $roles;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}

