<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="v_group")
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 */
class UserGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="nid", type="integer")
     * @ORM\Id
     *
     * @Groups({"staff"})
     */
    private $nid;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=60)
     *
     * @Groups({"staff"})
     */
    private $title;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="roles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     * })
     */
    private $user;

    /**
     * @return int
     */
    public function getNid()
    {
        return $this->nid;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}

