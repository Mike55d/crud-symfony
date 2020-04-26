<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * datesChilds
 *
 * @ORM\Table(name="dates_childs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\datesChildsRepository")
 */
class datesChilds
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
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="event", type="string", length=255)
     */
    private $event;

    /**
    * @ORM\ManyToOne(targetEntity="Childs")
    * @ORM\JoinColumn(name="child", referencedColumnName="id" , onDelete="CASCADE")
    */
    private $child;



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
     * Set event.
     *
     * @param string $event
     *
     * @return datesChilds
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set child.
     *
     * @param \AppBundle\Entity\Childs|null $child
     *
     * @return datesChilds
     */
    public function setChild(\AppBundle\Entity\Childs $child = null)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * Get child.
     *
     * @return \AppBundle\Entity\Childs|null
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set date.
     *
     * @param string $date
     *
     * @return datesChilds
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
}
