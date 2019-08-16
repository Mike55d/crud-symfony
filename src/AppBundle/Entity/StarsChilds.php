<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StarsChilds
 *
 * @ORM\Table(name="stars_childs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StarsChildsRepository")
 */
class StarsChilds
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
     * @var date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
    * @ORM\ManyToOne(targetEntity="Childs")
    * @ORM\JoinColumn(name="child", referencedColumnName="id")
    */

    private $child;

    /**
    * @ORM\ManyToOne(targetEntity="Estrella")
    * @ORM\JoinColumn(name="star", referencedColumnName="id")
    */
    private $star;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getChild(): ?Childs
    {
        return $this->child;
    }

    public function setChild(?Childs $child): self
    {
        $this->child = $child;

        return $this;
    }

    public function getStar(): ?Estrella
    {
        return $this->star;
    }

    public function setStar(?Estrella $star): self
    {
        $this->star = $star;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}

