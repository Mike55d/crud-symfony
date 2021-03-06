<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Childs
 *
 * @ORM\Table(name="childs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChildsRepository")
 */
class Childs
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
     * @ORM\Column(name="name", type="string", length=255 , nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255 , nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255 , nullable=true )
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255 , nullable=true)
     */
    private $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="datetime")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="parents", type="string", length=255 , nullable=true)
     */
    private $parents;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255 , nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", length=255 , nullable=true)
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="string", length=255 , nullable=true)
     */
    private $observations;

    /**
     * @var string
     *
     * @ORM\Column(name="barrio", type="string", length=255 , nullable=true)
     */
    private $barrio;

    /**
     * @var string
     *
     * @ORM\Column(name="colegio", type="string", length=255 , nullable=true)
     */
    private $colegio;

        /**
     * @var integer
     *
     * @ORM\Column(name="activo", type="integer", length=255 , nullable=true)
     */
    private $activo;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=255 , nullable=true)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=255 , nullable=true)
     */
    private $lng;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=255 , nullable=true)
     */
    private $grade;

        /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255 , nullable=true )
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="viernes", type="string", length=255 , nullable=true)
     */
    private $viernes;
    /**
     * @var string
     *
     * @ORM\Column(name="sabado", type="string", length=255 , nullable=true)
     */
    private $sabado;
    /**
     * @var string
     *
     * @ORM\Column(name="domingo", type="string", length=255 , nullable=true)
     */
    private $domingo;

    /**
    * @ORM\ManyToOne(targetEntity="Sede")
    * @ORM\JoinColumn(name="sede", referencedColumnName="id" , nullable=true)
    */
    private $sede;

    /**
    * @ORM\ManyToOne(targetEntity="Grupo")
    * @ORM\JoinColumn(name="grupo", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $grupo;

        /**
    * @ORM\ManyToOne(targetEntity="Courses")
    * @ORM\JoinColumn(name="course", referencedColumnName="id" , nullable=true ,onDelete="SET NULL")
    */
    private $course;

    /**
    * @ORM\ManyToOne(targetEntity="Institute")
    * @ORM\JoinColumn(name="institute", referencedColumnName="id" , nullable=true)
    */
    private $institute;

    /**
    * @ORM\ManyToOne(targetEntity="Telefonero")
    * @ORM\JoinColumn(name="telefonero", referencedColumnName="id" , nullable=true , onDelete="SET NULL")
    */
    private $telefonero;

/**
    * @ORM\ManyToOne(targetEntity="Ruta")
    * @ORM\JoinColumn(name="route", referencedColumnName="id" , nullable=true ,onDelete="SET NULL")
    */
    private $route;

       /**
     * @var boolean
     *
     * @ORM\Column(name="extra", type="boolean" , nullable=true )
     */
    private $extra;


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
     * Set name
     *
     * @param string $name
     *
     * @return Childs
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Childs
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
     * Set image
     *
     * @param string $image
     *
     * @return Childs
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Childs
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set parents
     *
     * @param string $parents
     *
     * @return Childs
     */
    public function setParents($parents)
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * Get parents
     *
     * @return string
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Childs
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Childs
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Childs
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set barrio
     *
     * @param string $barrio
     *
     * @return Childs
     */
    public function setBarrio($barrio)
    {
        $this->barrio = $barrio;

        return $this;
    }

    /**
     * Get barrio
     *
     * @return string
     */
    public function getBarrio()
    {
        return $this->barrio;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return Childs
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set sede
     *
     * @param \AppBundle\Entity\Sede $sede
     *
     * @return Childs
     */
    public function setSede(\AppBundle\Entity\Sede $sede = null)
    {
        $this->sede = $sede;

        return $this;
    }

    /**
     * Get sede
     *
     * @return \AppBundle\Entity\Sede
     */
    public function getSede()
    {
        return $this->sede;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return Childs
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * Set grupo
     *
     * @param \AppBundle\Entity\Grupo $grupo
     *
     * @return Childs
     */
    public function setGrupo(\AppBundle\Entity\Grupo $grupo = null)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \AppBundle\Entity\Grupo
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set institute
     *
     * @param \AppBundle\Entity\Institute $institute
     *
     * @return Childs
     */
    public function setInstitute(\AppBundle\Entity\Institute $institute = null)
    {
        $this->institute = $institute;

        return $this;
    }

    /**
     * Get institute
     *
     * @return \AppBundle\Entity\Institute
     */
    public function getInstitute()
    {
        return $this->institute;
    }

    /**
     * Set telefonero
     *
     * @param \AppBundle\Entity\Telefonero $telefonero
     *
     * @return Childs
     */
    public function setTelefonero(\AppBundle\Entity\Telefonero $telefonero = null)
    {
        $this->telefonero = $telefonero;

        return $this;
    }

    /**
     * Get telefonero
     *
     * @return \AppBundle\Entity\Telefonero
     */
    public function getTelefonero()
    {
        return $this->telefonero;
    }

    /**
     * Set route
     *
     * @param \AppBundle\Entity\Ruta $route
     *
     * @return Childs
     */
    public function setRoute(\AppBundle\Entity\Ruta $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \AppBundle\Entity\Ruta
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Childs
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Childs
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
     * Set lat
     *
     * @param string $lat
     *
     * @return Childs
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     *
     * @return Childs
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set colegio
     *
     * @param string $colegio
     *
     * @return Childs
     */
    public function setColegio($colegio)
    {
        $this->colegio = $colegio;

        return $this;
    }

    /**
     * Get colegio
     *
     * @return string
     */
    public function getColegio()
    {
        return $this->colegio;
    }

    /**
     * Set activo
     *
     * @param integer $activo
     *
     * @return Childs
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return integer
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Courses $course
     *
     * @return Childs
     */
    public function setCourse(\AppBundle\Entity\Courses $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \AppBundle\Entity\Courses
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set viernes
     *
     * @param string $viernes
     *
     * @return Childs
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return string
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado
     *
     * @param string $sabado
     *
     * @return Childs
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return string
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param string $domingo
     *
     * @return Childs
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return string
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set extra.
     *
     * @param bool|null $extra
     *
     * @return Childs
     */
    public function setExtra($extra = null)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra.
     *
     * @return bool|null
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
