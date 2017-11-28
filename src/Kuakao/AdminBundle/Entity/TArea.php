<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TArea
 *
 * @ORM\Table(name="t_area",indexes={@ORM\Index(name="listorder", columns={"listorder"})})
 * @ORM\Entity(repositoryClass="Kuakao\AdminBundle\Repository\TAreaRepository")
 */
class TArea
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="zone", type="smallint", nullable=false)
     */
    private $zone;

    /**
     * @var integer
     *
     * @ORM\Column(name="listorder", type="smallint", nullable=false)
     */
    private $listorder;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TArea
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
     * Set zone
     *
     * @param integer $zone
     * @return TArea
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return integer 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set listorder
     *
     * @param integer $listorder
     * @return TArea
     */
    public function setListorder($listorder)
    {
        $this->listorder = $listorder;

        return $this;
    }

    /**
     * Get listorder
     *
     * @return integer 
     */
    public function getListorder()
    {
        return $this->listorder;
    }
}
