<?php

namespace Kuakao\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TMenu
 *
 * @ORM\Table(name="t_menu", uniqueConstraints={@ORM\UniqueConstraint(name="routename", columns={"routename"})}, indexes={@ORM\Index(name="listorder", columns={"listorder"}), @ORM\Index(name="parentid", columns={"parentid"})})
 * @ORM\Entity(repositoryClass="Kuakao\AdminBundle\Repository\TMenuRepository")
 */
class TMenu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="smallint", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    public $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=true)
     */
    public $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="parentid", type="smallint", nullable=true)
     */
    public $parentid;

    /**
     * @var string
     *
     * @ORM\Column(name="routename", type="string", length=100, nullable=true)
     */
    public $routename;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=100, nullable=true)
     */
    public $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="listorder", type="smallint", nullable=true)
     */
    public $listorder;

    /**
     * @var string
     *
     * @ORM\Column(name="display", type="string", nullable=true)
     */
    public $display;



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
     * @return TMenu
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
     * Set parentid
     *
     * @param integer $parentid
     * @return TMenu
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return integer 
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set routename
     *
     * @param string $routename
     * @return TMenu
     */
    public function setRoutename($routename)
    {
        $this->routename = $routename;

        return $this;
    }

    /**
     * Get routename
     *
     * @return string 
     */
    public function getRoutename()
    {
        return $this->routename;
    }

    /**
     * Set url
     *
     * @param string $data
     * @return TMenu
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set listorder
     *
     * @param integer $listorder
     * @return TMenu
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

    /**
     * Set display
     *
     * @param string $display
     * @return TMenu
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Get display
     *
     * @return string 
     */
    public function getDisplay()
    {
        return $this->display;
    }
}
