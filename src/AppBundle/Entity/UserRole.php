<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="users_roles")
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 */
class UserRole
{
    private $roles = [
        1 => 'ROLE_ANONYMOUS_USER',
        2 => 'ROLE_AUTHENTICATED_USER',
        3 => 'ROLE_ADMINISTRATOR',
        4 => 'ROLE_FORM_TUTOR',
        5 => 'ROLE_HOMEWORK_COORDINATOR',
        6 => 'ROLE_OFFICE',
        7 => 'ROLE_PARENT',
        8 => 'ROLE_PUPIL',
        9 => 'ROLE_STAFF_ADMIN',
        10 => 'ROLE_TEACHER',
        11 => 'ROLE_SCHOOL_LEADERSHIP_TEAM',
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="rid", type="integer")
     * @ORM\Id
     *
     * @Groups({"staff"})
     */
    private $rid;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     * })
     */
    private $user;

    public function __toString()
    {
        return $this->roles[$this->getRid()];
    }

    /**
     * @return int
     */
    public function getRid()
    {
        return $this->rid;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @Groups({"staff"})
     *
     * @return mixed
     */
    public function getRole()
    {
        if (!in_array($this->getRid(), array_keys($this->roles))) {
            return $this->getRid() . ' (not found)';
        }
        return $this->roles[$this->getRid()];
    }
}

