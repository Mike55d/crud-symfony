<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Roles
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RolesRepository")
 */
class Roles
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
     * @var array
     *
     * @ORM\Column(name="role", type="json_array")
     */
    private $role;


    /**
    * @ORM\ManyToOne(targetEntity="Sede")
    * @ORM\JoinColumn(name="sede", referencedColumnName="id")
    */
    private $sede;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set role.
     *
     * @param array $role
     *
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set sede.
     *
     * @param \AppBundle\Entity\Sede|null $sede
     *
     * @return Roles
     */
    public function setSede(\AppBundle\Entity\Sede $sede = null)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede.
     *
     * @return \AppBundle\Entity\Sede|null
     */
    public function getSede()
    {
        return $this->sede;
    }
}
