<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permisos
 *
 * @ORM\Table(name="permisos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PermisosRepository")
 */
class Permisos
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


    /**
    * @ORM\ManyToOne(targetEntity="Sede")
    * @ORM\JoinColumn(name="sede", referencedColumnName="id" , onDelete="CASCADE")
    */

    private $sede;

     /**
     * @var array
     *
     * @ORM\Column(name="permisos", type="json_array")
     */
    private $permisos;



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
     * Set type.
     *
     * @param string $type
     *
     * @return Permisos
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set permisos.
     *
     * @param array $permisos
     *
     * @return Permisos
     */
    public function setPermisos($permisos)
    {
        $this->permisos = $permisos;

        return $this;
    }

    /**
     * Get permisos.
     *
     * @return array
     */
    public function getPermisos()
    {
        return $this->permisos;
    }

    /**
     * Set sede.
     *
     * @param \AppBundle\Entity\Sede|null $sede
     *
     * @return Permisos
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
